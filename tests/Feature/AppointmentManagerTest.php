<?php

namespace Tests\Feature;

use App\Livewire\AppointmentForm;
use App\Livewire\AppointmentList;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class AppointmentManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_appointment_edit_back_button_returns_to_the_previous_view(): void
    {
        $user = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => now()->addWeek(),
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);

        $this->actingAs($user)
            ->from(route('appointments.index', ['client' => $client->id]))
            ->get(route('appointments.edit', $appointment))
            ->assertOk()
            ->assertSeeHtml('onclick="if (document.referrer) { event.preventDefault(); window.history.back(); }"');
    }

    public function test_appointment_navigation_buttons_only_appear_on_the_exact_appointments_url(): void
    {
        $user = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        $this->actingAs($user)
            ->get(route('appointments.index'))
            ->assertOk()
            ->assertSeeHtml('data-appointment-navigation="sent"')
            ->assertDontSee('Todas las citas');

        $this->get(route('appointments.index', ['client' => $client->id]))
            ->assertOk()
            ->assertDontSeeHtml('data-appointment-navigation');

        $this->get(route('appointments.sent'))
            ->assertOk()
            ->assertSeeHtml('data-appointment-navigation="all"')
            ->assertSee('Todas las citas');
    }

    public function test_appointment_manager_can_create_an_appointment_for_a_client(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        Livewire::test(AppointmentForm::class)
            ->set('selectedClientId', $client->id)
            ->set('fecha', '2026-06-30')
            ->set('hora', '11:30')
            ->set('enviado', false)
            ->set('activo', true)
            ->call('save')
            ->assertSee('Cita creada correctamente.');

        $this->assertDatabaseHas('appointments', [
            'client_id' => $client->id,
            'fecha' => '2026-06-30 00:00:00',
            'hora' => '11:30',
            'enviado' => 0,
            'entregado' => 0,
            'activo' => 1,
        ]);

        Carbon::setTestNow();
    }

    public function test_appointment_create_can_send_whatsapp_immediately(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $admin = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        Config::set('whatsapp.driver', 'twilio');
        Config::set('whatsapp.message_mode', 'text');
        Config::set('whatsapp.twilio.account_sid', 'AC123');
        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.mode', 'sandbox');
        Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');

        Http::fake([
            'api.twilio.com/*/Messages.json' => Http::response([
                'sid' => 'SMAPPOINTMENTNOW123',
                'status' => 'delivered',
            ], 201),
        ]);

        $this->actingAs($admin);

        Livewire::test(AppointmentForm::class)
            ->set('selectedClientId', $client->id)
            ->set('fecha', '2026-06-30')
            ->set('hora', '11:30')
            ->set('sendImmediately', true)
            ->call('save')
            ->assertSee('Cita creada correctamente y WhatsApp enviado ahora.');

        $appointment = Appointment::query()->firstOrFail();

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://api.twilio.com/2010-04-01/Accounts/AC123/Messages.json'
                && $request['From'] === 'whatsapp:+14155238886'
                && $request['To'] === 'whatsapp:+34600111222'
                && $request['Body'] === 'Hola Ana te recordamos que el día 30/06/2026 tienes una cita a las 11:30 ; saludos Clínica Dental Eugenia';
        });

        $message = WhatsAppMessage::query()->firstOrFail();

        $this->assertTrue($appointment->enviado);
        $this->assertTrue($appointment->entregado);
        $this->assertNotNull($appointment->refresh()->whatsapp_sent_at);
        $this->assertNotNull($appointment->whatsapp_delivered_at);
        $this->assertSame($client->id, $message->client_id);
        $this->assertSame($appointment->id, $message->appointment_id);
        $this->assertSame(WhatsAppMessage::STATUS_SENT, $message->status);
        $this->assertSame('SMAPPOINTMENTNOW123', $message->provider_message_id);
        $this->assertTrue($message->metadata['immediate_send']);
        $this->assertSame('2026-06-23 09:00:00', $message->metadata['immediate_sent_at']);
        $this->assertNotNull($message->sent_at);

        Carbon::setTestNow();
    }

    public function test_appointment_form_rejects_today_and_sundays(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        Livewire::test(AppointmentForm::class)
            ->set('selectedClientId', $client->id)
            ->set('fecha', '2026-06-23')
            ->set('hora', '11:30')
            ->call('save')
            ->assertHasErrors('fecha');

        Livewire::test(AppointmentForm::class)
            ->set('selectedClientId', $client->id)
            ->set('fecha', '2026-06-28')
            ->set('hora', '11:30')
            ->call('save')
            ->assertHasErrors('fecha');

        $this->assertSame(0, Appointment::query()->count());

        Carbon::setTestNow();
    }

    public function test_appointment_page_can_open_selected_client_from_query_string(): void
    {
        $user = User::factory()->create();

        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Lucía',
            'apellidos' => 'Martín',
            'telefono' => '600123123',
        ]);

        $this->actingAs($user)
            ->get(route('appointments.create', ['client' => $client->id]))
            ->assertOk()
            ->assertSee('Lucía Martín')
            ->assertSee('Alta: 23/06/2026 09:00');

        Carbon::setTestNow();
    }

    public function test_client_search_is_limited_to_ten_results_without_pagination(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        foreach (range(1, 11) as $index) {
            Client::query()->create([
                'nombre' => 'Persona'.str_pad((string) $index, 2, '0', STR_PAD_LEFT),
                'apellidos' => 'Prueba',
                'telefono' => '60000000'.$index,
            ]);

            Carbon::setTestNow(Carbon::now()->addSecond());
        }

        $component = Livewire::test(AppointmentForm::class)
            ->set('filter_nombre', 'Persona');

        $html = $component->html();

        $this->assertStringContainsString('Hay más de 10 resultados, afina la búsqueda.', $html);
        $this->assertSame(10, substr_count($html, 'wire:key="appointment-form-client-'));
        $this->assertStringNotContainsString('Persona01 Prueba', $html);
        $this->assertStringContainsString('Persona11 Prueba', $html);

        Carbon::setTestNow();
    }

    public function test_appointment_create_page_hides_management_until_client_is_selected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('appointments.create'))
            ->assertOk()
            ->assertSee('Buscar cliente')
            ->assertDontSee('Gestión cita');
    }

    public function test_appointment_create_page_shows_management_when_client_is_selected(): void
    {
        $user = User::factory()->create();

        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Lucía',
            'apellidos' => 'Martín',
            'telefono' => '600123123',
        ]);

        $this->actingAs($user)
            ->get(route('appointments.create', ['client' => $client->id]))
            ->assertOk()
            ->assertSee('Gestión cita')
            ->assertSee('Cliente seleccionado')
            ->assertSee('Lucía Martín');

        Carbon::setTestNow();
    }

    public function test_appointment_manager_can_update_active_status_from_listing(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);

        WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
            'scheduled_for' => now()->addDay(),
            'message' => 'Recordatorio',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_PENDING,
        ]);

        Livewire::test(AppointmentList::class)
            ->call('updateActiveStatus', $appointment->id, false)
            ->assertSee('Estado pendiente actualizado.');

        $this->assertFalse($appointment->refresh()->activo);
        $this->assertSame(0, WhatsAppMessage::query()->where('appointment_id', $appointment->id)->count());

        Carbon::setTestNow();
    }

    public function test_appointment_list_can_send_whatsapp_immediately(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $admin = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);

        Config::set('whatsapp.driver', 'twilio');
        Config::set('whatsapp.message_mode', 'text');
        Config::set('whatsapp.twilio.account_sid', 'AC123');
        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.mode', 'sandbox');
        Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');

        Http::fake([
            'api.twilio.com/*/Messages.json' => Http::response([
                'sid' => 'SMAPPOINTMENTLIST123',
                'status' => 'delivered',
            ], 201),
        ]);

        $this->actingAs($admin);

        Livewire::withQueryParams(['client' => $client->id])
            ->test(AppointmentList::class)
            ->assertSee('Enviar ya')
            ->assertSeeHtml('appointments/'.$appointment->id.'/edit')
            ->call('sendNow', $appointment->id)
            ->assertSee('WhatsApp enviado ahora correctamente.');

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://api.twilio.com/2010-04-01/Accounts/AC123/Messages.json'
                && $request['From'] === 'whatsapp:+14155238886'
                && $request['To'] === 'whatsapp:+34600111222'
                && $request['Body'] === 'Hola Ana te recordamos que el día 30/06/2026 tienes una cita a las 11:30 ; saludos Clínica Dental Eugenia';
        });

        $message = WhatsAppMessage::query()->firstOrFail();

        $appointment->refresh();

        $this->assertTrue($appointment->enviado);
        $this->assertTrue($appointment->entregado);
        $this->assertSame($appointment->id, $message->appointment_id);
        $this->assertSame(WhatsAppMessage::STATUS_SENT, $message->status);
        $this->assertSame('SMAPPOINTMENTLIST123', $message->provider_message_id);

        Carbon::setTestNow();
    }

    public function test_appointment_list_marks_delivered_when_provider_log_is_read(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'entregado' => false,
            'activo' => true,
        ]);

        WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
            'scheduled_for' => now(),
            'message' => 'Recordatorio',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_SENT,
            'provider_payload' => [
                'provider' => 'twilio',
                'raw' => ['status' => 'delivered'],
            ],
        ]);

        Livewire::test(AppointmentList::class)
            ->set('filter_enviado', true)
            ->assertSee('11:30');

        Livewire::test(AppointmentList::class)
            ->call('syncDeliveryStatuses')
            ->assertSee('Se actualizaron 1 cita(s) como entregadas.');

        $this->assertTrue($appointment->refresh()->entregado);

        Carbon::setTestNow();
    }

    public function test_appointment_list_can_sync_delivery_statuses_manually(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'entregado' => false,
            'activo' => true,
        ]);

        $message = WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
            'scheduled_for' => now(),
            'message' => 'Recordatorio',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_SENT,
            'provider_payload' => [
                'provider' => 'twilio',
                'raw' => ['status' => 'sent'],
            ],
        ]);

        $component = Livewire::test(AppointmentList::class)
            ->assertSee('Leer logs');

        $message->update([
            'provider_payload' => [
                'provider' => 'twilio',
                'raw' => ['status' => 'delivered'],
            ],
        ]);

        $component
            ->call('syncDeliveryStatuses')
            ->assertSee('Se actualizaron 1 cita(s) como entregadas.');

        $this->assertTrue($appointment->refresh()->entregado);

        Carbon::setTestNow();
    }

    public function test_appointment_list_shows_sent_delivered_and_read_timestamps(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'entregado' => true,
            'whatsapp_sent_at' => '2026-06-23 08:05:00',
            'whatsapp_delivered_at' => '2026-06-23 08:10:00',
            'whatsapp_read_at' => '2026-06-23 09:15:00',
            'activo' => true,
        ]);

        WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
            'scheduled_for' => now(),
            'message' => 'Recordatorio',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_SENT,
            'provider_message_id' => 'SMTOOLTIP123',
        ]);

        Livewire::test(AppointmentList::class)
            ->set('filter_entregado', true)
            ->assertSee('23/06/2026 08:05')
            ->assertSee('23/06/2026 08:10')
            ->assertSeeHtml('title="Message SID: SMTOOLTIP123"')
            ->assertSee('Leído')
            ->assertSeeHtml('text-green-400')
            ->assertSee('Sí');

        Carbon::setTestNow();
    }

    public function test_appointment_list_does_not_poll_twilio_on_render(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'entregado' => false,
            'whatsapp_sent_at' => '2026-06-23 08:05:00',
            'whatsapp_delivered_at' => '2026-06-23 08:10:00',
            'activo' => true,
        ]);

        Http::fake();

        Livewire::test(AppointmentList::class)
            ->set('filter_enviado', true)
            ->assertSee('11:30');

        Http::assertNothingSent();

        Carbon::setTestNow();
    }

    public function test_appointment_list_force_sync_polls_old_twilio_messages_and_marks_read(): void
    {
        Carbon::setTestNow('2026-06-23 12:00:00');

        Config::set('whatsapp.twilio.account_sid', 'AC123');
        Config::set('whatsapp.twilio.auth_token', 'test-token');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'entregado' => true,
            'whatsapp_sent_at' => '2026-06-22 11:59:00',
            'whatsapp_delivered_at' => '2026-06-22 12:00:00',
            'activo' => true,
        ]);

        WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
            'scheduled_for' => now()->subDay()->subMinute(),
            'message' => 'Recordatorio',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_SENT,
            'sent_at' => now()->subDay()->subMinute(),
            'provider_message_id' => 'SMOLD123',
            'provider_payload' => [
                'provider' => 'twilio',
                'raw' => ['status' => 'delivered'],
            ],
        ]);

        Http::fake([
            'api.twilio.com/*/Messages/SMOLD123.json' => Http::response([
                'sid' => 'SMOLD123',
                'status' => 'read',
            ]),
        ]);

        Livewire::test(AppointmentList::class)
            ->call('syncDeliveryStatuses')
            ->assertSee('Se actualizaron 1 cita(s) como entregadas.')
            ->assertSee('Última actualización: 23/06/2026 12:00');

        $appointment->refresh();

        $this->assertTrue($appointment->entregado);
        $this->assertSame('2026-06-23 12:00:00', $appointment->whatsapp_read_at?->toDateTimeString());
        Http::assertSent(fn ($request): bool => str_contains($request->url(), '/Messages/SMOLD123.json'));

        Livewire::test(AppointmentList::class)
            ->assertSee('Última actualización: 23/06/2026 12:00');

        Carbon::setTestNow();
    }

    public function test_appointment_list_toggles_force_sync_and_show_last_update_time(): void
    {
        Carbon::setTestNow('2026-06-23 12:00:00');

        Config::set('whatsapp.twilio.account_sid', 'AC123');
        Config::set('whatsapp.twilio.auth_token', 'test-token');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'entregado' => true,
            'whatsapp_sent_at' => '2026-06-22 11:59:00',
            'whatsapp_delivered_at' => '2026-06-22 12:00:00',
            'activo' => true,
        ]);

        WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
            'scheduled_for' => now()->subDay()->subMinute(),
            'message' => 'Recordatorio',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_SENT,
            'sent_at' => now()->subDay()->subMinute(),
            'provider_message_id' => 'SMTOGGLEREAD123',
            'provider_payload' => [
                'provider' => 'twilio',
                'raw' => ['status' => 'delivered'],
            ],
        ]);

        Http::fake([
            'api.twilio.com/*/Messages/SMTOGGLEREAD123.json' => Http::response([
                'sid' => 'SMTOGGLEREAD123',
                'status' => 'read',
            ]),
        ]);

        Livewire::test(AppointmentList::class)
            ->set('filter_entregado', true)
            ->assertSee('Última actualización: 23/06/2026 12:00');

        $this->assertSame('2026-06-23 12:00:00', $appointment->refresh()->whatsapp_read_at?->toDateTimeString());
        Http::assertSent(fn ($request): bool => str_contains($request->url(), '/Messages/SMTOGGLEREAD123.json'));

        Carbon::setTestNow();
    }

    public function test_global_appointment_list_links_rows_to_client_appointments_without_send_now_button(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);

        Livewire::test(AppointmentList::class)
            ->assertDontSee('Enviar ya')
            ->assertSeeHtml("onclick=\"window.location='".route('appointments.index', ['client' => $client->id])."'\"")
            ->assertSeeHtml('href="'.route('appointments.index', ['client' => $client->id]).'"');

        Carbon::setTestNow();
    }

    public function test_appointment_list_shows_one_row_per_client_with_a_badge_for_multiple_appointments(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $firstClient = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        Appointment::query()->create([
            'client_id' => $firstClient->id,
            'fecha' => '2026-06-24',
            'hora' => '10:17',
            'enviado' => false,
            'activo' => true,
        ]);
        Appointment::query()->create([
            'client_id' => $firstClient->id,
            'fecha' => '2026-06-30',
            'hora' => '11:23',
            'enviado' => false,
            'activo' => true,
        ]);

        $secondClient = Client::query()->create([
            'nombre' => 'Luis',
            'apellidos' => 'Gómez',
            'telefono' => '+34699111222',
        ]);

        Appointment::query()->create([
            'client_id' => $secondClient->id,
            'fecha' => '2026-06-28',
            'hora' => '09:45',
            'enviado' => false,
            'activo' => true,
        ]);

        $html = Livewire::test(AppointmentList::class)->html();

        $this->assertSame(2, substr_count($html, 'wire:key="appointment-'));
        $this->assertStringContainsString('3 citas', $html);

        Livewire::test(AppointmentList::class)
            ->assertSee('Ana Pérez')
            ->assertSee('10:17')
            ->assertDontSee('11:23')
            ->assertSeeHtml('aria-label="2 citas"');

        Carbon::setTestNow();
    }

    public function test_appointment_list_does_not_allow_sending_inactive_appointments(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => false,
        ]);

        Livewire::withQueryParams(['client' => $client->id])
            ->test(AppointmentList::class)
            ->assertDontSee('Enviar ya')
            ->call('sendNow', $appointment->id)
            ->assertSee('Las citas no pendientes no pueden enviarse.');

        $this->assertFalse($appointment->refresh()->enviado);
        $this->assertSame(0, WhatsAppMessage::query()->where('appointment_id', $appointment->id)->count());

        Carbon::setTestNow();
    }

    public function test_appointment_list_can_be_filtered_by_client_from_query_string(): void
    {
        $firstClient = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $secondClient = Client::query()->create([
            'nombre' => 'Luis',
            'apellidos' => 'Gómez',
            'telefono' => '+34699111222',
        ]);

        Appointment::query()->create([
            'client_id' => $firstClient->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);
        Appointment::query()->create([
            'client_id' => $firstClient->id,
            'fecha' => '2026-07-05',
            'hora' => '12:45',
            'enviado' => false,
            'activo' => true,
        ]);
        Appointment::query()->create([
            'client_id' => $secondClient->id,
            'fecha' => '2026-07-01',
            'hora' => '09:00',
            'enviado' => false,
            'activo' => true,
        ]);

        Livewire::withQueryParams(['client' => $firstClient->id])
            ->test(AppointmentList::class)
            ->assertSee('Ana Pérez')
            ->assertSee('11:30')
            ->assertSee('12:45')
            ->assertSee('2 citas')
            ->assertDontSee('Luis Gómez')
            ->assertDontSee('09:00');
    }

    public function test_client_appointment_list_shows_upcoming_by_default_and_can_show_all_or_past(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        foreach ([
            ['fecha' => '2026-06-22', 'hora' => '08:00', 'enviado' => false, 'activo' => true],
            ['fecha' => '2026-06-24', 'hora' => '09:00', 'enviado' => false, 'activo' => true],
            ['fecha' => '2026-06-25', 'hora' => '10:00', 'enviado' => true, 'activo' => true],
            ['fecha' => '2026-06-26', 'hora' => '11:00', 'enviado' => false, 'activo' => false],
        ] as $appointment) {
            Appointment::query()->create(['client_id' => $client->id, ...$appointment]);
        }

        Livewire::withQueryParams(['client' => $client->id])
            ->test(AppointmentList::class)
            ->assertSet('dateFilter', 'upcoming')
            ->assertDontSee('08:00')
            ->assertSee('09:00')
            ->assertSee('10:00')
            ->assertSee('11:00')
            ->set('dateFilter', 'all')
            ->assertSee('08:00')
            ->assertSee('09:00')
            ->set('dateFilter', 'past')
            ->assertSee('08:00')
            ->assertDontSee('09:00')
            ->assertDontSee('10:00')
            ->assertDontSee('11:00');

        Carbon::setTestNow();
    }

    public function test_client_appointment_list_can_delete_selected_appointments_in_bulk(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $otherClient = Client::query()->create([
            'nombre' => 'Luis',
            'apellidos' => 'Gómez',
            'telefono' => '+34699111222',
        ]);

        $firstAppointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-24',
            'hora' => '09:00',
            'enviado' => false,
            'activo' => true,
        ]);
        $secondAppointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-25',
            'hora' => '10:00',
            'enviado' => false,
            'activo' => true,
        ]);
        $pastAppointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-22',
            'hora' => '08:00',
            'enviado' => false,
            'activo' => true,
        ]);
        $otherAppointment = Appointment::query()->create([
            'client_id' => $otherClient->id,
            'fecha' => '2026-06-26',
            'hora' => '11:00',
            'enviado' => false,
            'activo' => true,
        ]);

        Livewire::withQueryParams(['client' => $client->id])
            ->test(AppointmentList::class)
            ->assertSee('Seleccionar todas las citas visibles')
            ->call('toggleVisibleAppointments', [$firstAppointment->id, $secondAppointment->id])
            ->assertSee('Deseleccionar todas las citas visibles')
            ->assertSee('2 citas seleccionadas')
            ->assertSet('selectedAppointmentIds', [$firstAppointment->id, $secondAppointment->id])
            ->call('confirmBulkDelete')
            ->assertSet('bulkDeleteConfirmationOpen', true)
            ->set('dateFilter', 'all')
            ->assertSet('selectedAppointmentIds', [])
            ->assertSet('bulkDeleteConfirmationOpen', false)
            ->assertSeeHtml('wire:key="select-all-appointments-all-0-0-0"')
            ->set('dateFilter', 'upcoming')
            ->set('selectedAppointmentIds', [$firstAppointment->id, $secondAppointment->id])
            ->call('confirmBulkDelete')
            ->set('filter_activo', true)
            ->assertSet('selectedAppointmentIds', [])
            ->assertSet('bulkDeleteConfirmationOpen', false)
            ->assertSeeHtml('wire:key="select-all-appointments-upcoming-0-1-0"')
            ->set('filter_activo', false)
            ->set('selectedAppointmentIds', [$firstAppointment->id, $secondAppointment->id, $otherAppointment->id])
            ->call('confirmBulkDelete')
            ->assertSet('bulkDeleteConfirmationOpen', true)
            ->assertSee('Eliminar citas seleccionadas')
            ->assertSee('Esta acción no se puede deshacer.')
            ->assertSeeHtml('x-trap.noscroll="modalOpen"')
            ->call('deleteSelected')
            ->assertRedirect(route('appointments.index'));

        $this->assertSoftDeleted($firstAppointment);
        $this->assertSoftDeleted($secondAppointment);
        $this->assertModelExists($pastAppointment);
        $this->assertModelExists($otherAppointment);
        $this->assertSame('No hay citas para el cliente Ana Pérez', session('status'));

        Carbon::setTestNow();
    }

    public function test_client_appointment_list_can_activate_and_deactivate_selected_appointments_in_bulk(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $otherClient = Client::query()->create([
            'nombre' => 'Luis',
            'apellidos' => 'Gómez',
            'telefono' => '+34699111222',
        ]);
        $appointments = collect([
            Appointment::query()->create(['client_id' => $client->id, 'fecha' => '2026-06-24', 'hora' => '09:00', 'enviado' => false, 'activo' => true]),
            Appointment::query()->create(['client_id' => $client->id, 'fecha' => '2026-06-25', 'hora' => '10:00', 'enviado' => false, 'activo' => true]),
        ]);
        $otherAppointment = Appointment::query()->create([
            'client_id' => $otherClient->id,
            'fecha' => '2026-06-26',
            'hora' => '11:00',
            'enviado' => false,
            'activo' => true,
        ]);

        $component = Livewire::withQueryParams(['client' => $client->id])
            ->test(AppointmentList::class)
            ->set('selectedAppointmentIds', [...$appointments->pluck('id'), $otherAppointment->id])
            ->assertSee('Activar seleccionadas')
            ->assertSee('Desactivar seleccionadas')
            ->call('updateSelectedActiveStatus', false)
            ->assertSet('selectedAppointmentIds', []);

        $appointments->each(fn (Appointment $appointment) => $this->assertFalse($appointment->fresh()->activo));
        $this->assertTrue($otherAppointment->fresh()->activo);

        $component
            ->set('selectedAppointmentIds', $appointments->pluck('id')->all())
            ->call('updateSelectedActiveStatus', true);

        $appointments->each(fn (Appointment $appointment) => $this->assertTrue($appointment->fresh()->activo));

        Carbon::setTestNow();
    }

    public function test_appointment_list_paginates_fifteen_pending_appointments_per_page(): void
    {
        for ($appointmentNumber = 1; $appointmentNumber <= 16; $appointmentNumber++) {
            $client = Client::query()->create([
                'nombre' => 'Cliente '.$appointmentNumber,
                'apellidos' => 'Prueba',
                'telefono' => '+3460011'.str_pad((string) $appointmentNumber, 4, '0', STR_PAD_LEFT),
            ]);

            Appointment::query()->create([
                'client_id' => $client->id,
                'fecha' => '2026-07-'.str_pad((string) $appointmentNumber, 2, '0', STR_PAD_LEFT),
                'hora' => '09:00',
                'enviado' => false,
                'activo' => true,
            ]);
        }

        $html = Livewire::test(AppointmentList::class)->html();

        $this->assertSame(15, substr_count($html, 'wire:key="appointment-'));
    }

    public function test_appointment_list_shows_pending_appointments_by_default_and_non_pending_with_toggle(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $futurePendingClient = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        $futurePendingAppointment = Appointment::query()->create([
            'client_id' => $futurePendingClient->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);

        $pastPendingClient = Client::query()->create([
            'nombre' => 'Marta',
            'apellidos' => 'López',
            'telefono' => '+34600333444',
        ]);
        $pastPendingAppointment = Appointment::query()->create([
            'client_id' => $pastPendingClient->id,
            'fecha' => '2026-06-01',
            'hora' => '10:15',
            'enviado' => false,
            'activo' => true,
        ]);

        $sentFutureClient = Client::query()->create([
            'nombre' => 'Sara',
            'apellidos' => 'Núñez',
            'telefono' => '+34600444555',
        ]);
        Appointment::query()->create([
            'client_id' => $sentFutureClient->id,
            'fecha' => '2026-06-30',
            'hora' => '12:30',
            'enviado' => true,
            'activo' => true,
        ]);

        $sentPastClient = Client::query()->create([
            'nombre' => 'Diego',
            'apellidos' => 'Vega',
            'telefono' => '+34600555666',
        ]);
        Appointment::query()->create([
            'client_id' => $sentPastClient->id,
            'fecha' => '2026-06-01',
            'hora' => '14:30',
            'enviado' => true,
            'activo' => true,
        ]);

        $inactiveFutureClient = Client::query()->create([
            'nombre' => 'Elena',
            'apellidos' => 'Ruiz',
            'telefono' => '+34600666777',
        ]);
        Appointment::query()->create([
            'client_id' => $inactiveFutureClient->id,
            'fecha' => '2026-06-30',
            'hora' => '13:30',
            'enviado' => false,
            'activo' => false,
        ]);

        Livewire::test(AppointmentList::class)
            ->assertSee('Pendiente')
            ->assertSee('Supendidas')
            ->assertSee('11:30')
            ->assertSeeHtml('wire:key="appointment-'.$futurePendingAppointment->id.'"')
            ->assertDontSee('10:15')
            ->assertDontSee('12:30')
            ->assertDontSee('14:30')
            ->assertDontSee('13:30')
            ->set('filter_activo', true)
            ->assertSee('Marta López')
            ->assertSee('10:15')
            ->assertSee('Elena Ruiz')
            ->assertSee('13:30')
            ->assertSee('Diego Vega')
            ->assertSee('14:30')
            ->assertSeeHtml('wire:key="appointment-'.$pastPendingAppointment->id.'"')
            ->assertDontSee('11:30')
            ->assertDontSee('12:30')
            ->assertSet('filter_enviado', false);

        Carbon::setTestNow();
    }

    public function test_active_filter_turns_off_sent_toggle(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);

        Livewire::test(AppointmentList::class)
            ->set('filter_activo', true)
            ->assertSet('filter_enviado', false);

        Livewire::test(AppointmentList::class)
            ->set('filter_activo', true)
            ->set('filter_enviado', true)
            ->assertSet('filter_activo', false)
            ->assertSet('filter_enviado', true);

        Carbon::setTestNow();
    }

    public function test_sent_filter_turns_off_active_toggle(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'activo' => true,
        ]);

        Livewire::test(AppointmentList::class)
            ->set('filter_activo', true)
            ->set('filter_enviado', true)
            ->assertSet('filter_activo', false)
            ->assertSeeHtml('disabled');

        Carbon::setTestNow();
    }

    public function test_future_appointments_can_be_deactivated_from_active_toggle(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);

        WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
            'scheduled_for' => now()->addDay(),
            'message' => 'Recordatorio',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_PENDING,
        ]);

        Livewire::test(AppointmentList::class)
            ->call('updateActiveStatus', $appointment->id, false)
            ->assertSee('Estado pendiente actualizado.');

        $this->assertFalse($appointment->refresh()->activo);
        $this->assertSame(0, WhatsAppMessage::query()->where('appointment_id', $appointment->id)->count());

        Carbon::setTestNow();
    }

    public function test_past_appointments_do_not_allow_active_status_changes_from_listing(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-01',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);

        Livewire::test(AppointmentList::class)
            ->call('updateActiveStatus', $appointment->id, false)
            ->assertSee('Esta cita no se puede modificar. Solo se puede eliminar.');

        $this->assertTrue($appointment->refresh()->activo);

        Carbon::setTestNow();
    }

    public function test_sent_appointments_do_not_allow_active_status_changes_from_listing(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'activo' => true,
        ]);

        Livewire::test(AppointmentList::class)
            ->call('updateActiveStatus', $appointment->id, false)
            ->assertSee('Esta cita no se puede modificar. Solo se puede eliminar.');

        $this->assertTrue($appointment->refresh()->activo);

        Carbon::setTestNow();
    }

    public function test_locked_appointments_are_muted_and_only_show_delete_action(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'activo' => true,
        ]);

        Livewire::test(AppointmentList::class)
            ->set('filter_enviado', true)
            ->assertSeeHtml('bg-slate-900/50 text-slate-400')
            ->assertSeeHtml('aria-label="Eliminar cita"')
            ->assertSeeHtml('href="'.route('appointments.index', ['client' => $client->id]).'"')
            ->assertDontSeeHtml('aria-label="Editar cita"');

        Carbon::setTestNow();
    }

    public function test_appointment_list_asks_for_confirmation_before_deleting(): void
    {
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);

        Livewire::test(AppointmentList::class)
            ->call('confirmDelete', $appointment->id)
            ->assertSee('Eliminar cita')
            ->assertSee('Esta acción no se puede deshacer.')
            ->assertSeeHtml('x-trap.noscroll="modalOpen"')
            ->call('deleteConfirmed');

        $this->assertSoftDeleted($appointment);
    }

    public function test_appointment_list_page_is_separate_from_appointment_form(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('appointments.index'))
            ->assertOk()
            ->assertSee('Citas registradas')
            ->assertSee('Nueva cita')
            ->assertDontSee('Buscar cliente');
    }

    public function test_sent_appointments_page_shows_only_sent_appointments(): void
    {
        $user = User::factory()->create();

        $sentClient = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Enviada',
            'telefono' => '+34600111222',
        ]);
        $pendingClient = Client::query()->create([
            'nombre' => 'Berto',
            'apellidos' => 'Pendiente',
            'telefono' => '+34600113333',
        ]);

        Appointment::query()->create([
            'client_id' => $sentClient->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'activo' => true,
        ]);
        Appointment::query()->create([
            'client_id' => $pendingClient->id,
            'fecha' => '2026-07-01',
            'hora' => '12:00',
            'enviado' => false,
            'activo' => true,
        ]);

        $this->actingAs($user)
            ->get(route('appointments.sent'))
            ->assertOk()
            ->assertSee('Citas enviadas')
            ->assertSee('Ana Enviada')
            ->assertDontSee('Berto Pendiente')
            ->assertDontSee('Notificaciones');
    }

    public function test_appointment_edit_page_loads_selected_appointment(): void
    {
        $user = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);

        $this->actingAs($user)
            ->get(route('appointments.edit', $appointment))
            ->assertOk()
            ->assertSee('Editar cita')
            ->assertDontSee('Buscar cliente')
            ->assertSee('Ana Pérez');
    }

    public function test_appointment_edit_can_update_active_status(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);

        Livewire::test(AppointmentForm::class)
            ->set('isEditing', true)
            ->set('selectedAppointmentId', $appointment->id)
            ->set('selectedClientId', $client->id)
            ->set('fecha', '2026-06-30')
            ->set('hora', '11:30')
            ->set('enviado', false)
            ->set('activo', false)
            ->call('save')
            ->assertSee('Cita actualizada correctamente.')
            ->assertDontSee('Buscar cliente');

        $this->assertFalse($appointment->refresh()->activo);

        Carbon::setTestNow();
    }

    public function test_appointment_edit_can_send_whatsapp_immediately(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $admin = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);

        Config::set('whatsapp.driver', 'twilio');
        Config::set('whatsapp.message_mode', 'text');
        Config::set('whatsapp.twilio.account_sid', 'AC123');
        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.mode', 'sandbox');
        Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');

        Http::fake([
            'api.twilio.com/*/Messages.json' => Http::response([
                'sid' => 'SMAPPOINTMENTEDIT123',
                'status' => 'delivered',
            ], 201),
        ]);

        $this->actingAs($admin);

        $component = Livewire::test(AppointmentForm::class)
            ->set('isEditing', true)
            ->set('selectedAppointmentId', $appointment->id)
            ->set('selectedClientId', $client->id)
            ->set('fecha', '2026-06-30')
            ->set('hora', '11:30')
            ->set('enviado', false)
            ->set('activo', true);

        $this->assertStringContainsString('wire:click="sendNow"', $component->html());
        $this->assertStringNotContainsString('wire:click="sendNow" disabled="disabled"', $component->html());

        $component
            ->call('sendNow')
            ->assertSee('WhatsApp enviado ahora correctamente.');

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://api.twilio.com/2010-04-01/Accounts/AC123/Messages.json'
                && $request['From'] === 'whatsapp:+14155238886'
                && $request['To'] === 'whatsapp:+34600111222'
                && $request['Body'] === 'Hola Ana te recordamos que el día 30/06/2026 tienes una cita a las 11:30 ; saludos Clínica Dental Eugenia';
        });

        $message = WhatsAppMessage::query()->firstOrFail();

        $appointment->refresh();

        $this->assertTrue($appointment->enviado);
        $this->assertTrue($appointment->entregado);
        $this->assertSame($appointment->id, $message->appointment_id);
        $this->assertSame(WhatsAppMessage::STATUS_SENT, $message->status);
        $this->assertSame('SMAPPOINTMENTEDIT123', $message->provider_message_id);
        $this->assertStringNotContainsString('Esta cita ya fue enviada o pertenece al pasado.', $component->html());
        $this->assertStringNotContainsString('Enviar ya', $component->html());
        $this->assertStringNotContainsString('Guardar cambios', $component->html());
        $this->assertStringNotContainsString('Cancelar', $component->html());
        $this->assertStringContainsString('Volver', $component->html());

        Carbon::setTestNow();
    }

    public function test_appointment_edit_marks_sent_when_provider_status_is_sent(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $admin = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);

        Config::set('whatsapp.driver', 'twilio');
        Config::set('whatsapp.message_mode', 'text');
        Config::set('whatsapp.twilio.account_sid', 'AC123');
        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.mode', 'sandbox');
        Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');

        Http::fake([
            'api.twilio.com/*/Messages.json' => Http::response([
                'sid' => 'SMSENT123',
                'status' => 'sent',
            ], 201),
        ]);

        $this->actingAs($admin);

        Livewire::test(AppointmentForm::class)
            ->set('selectedAppointmentId', $appointment->id)
            ->set('selectedClientId', $client->id)
            ->set('fecha', '2026-06-30')
            ->set('hora', '11:30')
            ->set('enviado', false)
            ->set('activo', true)
            ->call('sendNow')
            ->assertSee('WhatsApp enviado ahora correctamente.');

        $message = WhatsAppMessage::query()->firstOrFail();

        $appointment->refresh();

        $this->assertTrue($appointment->enviado);
        $this->assertFalse($appointment->entregado);
        $this->assertSame(WhatsAppMessage::STATUS_SENT, $message->status);
        $this->assertSame('SMSENT123', $message->provider_message_id);
        $this->assertNotNull($message->sent_at);

        Carbon::setTestNow();
    }

    public function test_appointment_edit_marks_sent_when_provider_status_is_queued(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);

        Config::set('whatsapp.driver', 'twilio');
        Config::set('whatsapp.message_mode', 'text');
        Config::set('whatsapp.twilio.account_sid', 'AC123');
        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.mode', 'sandbox');
        Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');

        Http::fake([
            'api.twilio.com/*/Messages.json' => Http::response([
                'sid' => 'SMQUEUED123',
                'status' => 'queued',
            ], 201),
        ]);

        Livewire::test(AppointmentForm::class)
            ->set('selectedAppointmentId', $appointment->id)
            ->set('selectedClientId', $client->id)
            ->set('fecha', '2026-06-30')
            ->set('hora', '11:30')
            ->set('enviado', false)
            ->set('activo', true)
            ->call('sendNow')
            ->assertSee('WhatsApp enviado ahora correctamente.');

        $message = WhatsAppMessage::query()->firstOrFail();

        $appointment->refresh();

        $this->assertTrue($appointment->enviado);
        $this->assertFalse($appointment->entregado);
        $this->assertSame(WhatsAppMessage::STATUS_SENT, $message->status);
        $this->assertNotNull($message->sent_at);
        $this->assertSame('SMQUEUED123', $message->provider_message_id);

        Carbon::setTestNow();
    }

    public function test_appointment_edit_shows_provider_failure_reason_without_marking_sent(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);

        Config::set('whatsapp.driver', 'twilio');
        Config::set('whatsapp.message_mode', 'text');
        Config::set('whatsapp.twilio.account_sid', 'AC123');
        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.mode', 'sandbox');
        Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');

        Http::fake([
            'api.twilio.com/*/Messages.json' => Http::response([
                'sid' => 'SMUNDELIVERED123',
                'status' => 'undelivered',
                'error_code' => 63016,
                'error_message' => 'Sandbox recipient is not joined.',
            ], 201),
        ]);

        Livewire::test(AppointmentForm::class)
            ->set('selectedAppointmentId', $appointment->id)
            ->set('selectedClientId', $client->id)
            ->set('fecha', '2026-06-30')
            ->set('hora', '11:30')
            ->set('enviado', false)
            ->set('activo', true)
            ->call('sendNow')
            ->assertSee('No se pudo enviar el WhatsApp.')
            ->assertSee('estado: undelivered')
            ->assertSee('código: 63016')
            ->assertSee('La cita no se ha marcado como enviada.');

        $message = WhatsAppMessage::query()->firstOrFail();

        $appointment->refresh();

        $this->assertFalse($appointment->enviado);
        $this->assertFalse($appointment->entregado);
        $this->assertSame(WhatsAppMessage::STATUS_FAILED, $message->status);
        $this->assertNull($message->sent_at);
        $this->assertSame('SMUNDELIVERED123', $message->provider_message_id);
        $this->assertStringContainsString('undelivered', $message->last_error);
        $this->assertStringContainsString('63016', $message->last_error);

        Carbon::setTestNow();
    }

    public function test_past_appointment_edit_cannot_send_whatsapp_immediately(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-01',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);

        Http::fake();

        $component = Livewire::test(AppointmentForm::class)
            ->set('selectedAppointmentId', $appointment->id)
            ->set('selectedClientId', $client->id)
            ->set('fecha', '2026-06-01')
            ->set('hora', '11:30')
            ->set('enviado', false)
            ->set('activo', true)
            ->assertSee('Enviar ya')
            ->call('sendNow')
            ->assertSee('Las citas pasadas no pueden enviarse.');

        Http::assertNothingSent();

        $this->assertFalse($appointment->refresh()->enviado);
        $this->assertSame(0, WhatsAppMessage::query()->count());
        $this->assertStringContainsString('disabled="disabled"', $component->html());

        Carbon::setTestNow();
    }

    public function test_sent_appointments_cannot_be_updated_from_form(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'activo' => true,
        ]);

        Livewire::test(AppointmentForm::class)
            ->set('selectedAppointmentId', $appointment->id)
            ->set('selectedClientId', $client->id)
            ->set('fecha', '2026-07-01')
            ->set('hora', '10:00')
            ->set('enviado', false)
            ->set('activo', false)
            ->call('save')
            ->assertSee('Esta cita no se puede modificar. Solo se puede eliminar.');

        $appointment->refresh();

        $this->assertSame('2026-06-30', $appointment->fecha->toDateString());
        $this->assertSame('11:30', $appointment->hora);
        $this->assertTrue($appointment->enviado);
        $this->assertTrue($appointment->activo);

        Carbon::setTestNow();
    }

    public function test_past_appointments_cannot_be_updated_from_form(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-01',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);

        Livewire::test(AppointmentForm::class)
            ->set('selectedAppointmentId', $appointment->id)
            ->set('selectedClientId', $client->id)
            ->set('fecha', '2026-07-01')
            ->set('hora', '10:00')
            ->set('enviado', false)
            ->set('activo', false)
            ->call('save')
            ->assertSee('Esta cita no se puede modificar. Solo se puede eliminar.');

        $appointment->refresh();

        $this->assertSame('2026-06-01', $appointment->fecha->toDateString());
        $this->assertSame('11:30', $appointment->hora);
        $this->assertFalse($appointment->enviado);
        $this->assertTrue($appointment->activo);

        Carbon::setTestNow();
    }

    public function test_appointment_list_filters_by_client_name_and_surname(): void
    {
        $firstClient = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $secondClient = Client::query()->create([
            'nombre' => 'Luis',
            'apellidos' => 'Gómez',
            'telefono' => '+34699111222',
        ]);

        Appointment::query()->create([
            'client_id' => $firstClient->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);
        Appointment::query()->create([
            'client_id' => $secondClient->id,
            'fecha' => '2026-07-01',
            'hora' => '09:00',
            'enviado' => false,
            'activo' => true,
        ]);

        Livewire::test(AppointmentList::class)
            ->set('filter_nombre', 'Ana')
            ->assertSee('Ana Pérez')
            ->assertDontSee('Luis Gómez')
            ->set('filter_nombre', '')
            ->set('filter_apellidos', 'Gómez')
            ->assertSee('Luis Gómez')
            ->assertDontSee('Ana Pérez');
    }

    public function test_appointment_list_orders_by_date_and_then_time(): void
    {
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        $lateNextDay = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-01',
            'hora' => '12:00',
            'enviado' => false,
            'activo' => true,
        ]);
        $earlyFirstDay = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '09:00',
            'enviado' => false,
            'activo' => true,
        ]);
        $earlyNextDay = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-01',
            'hora' => '08:00',
            'enviado' => false,
            'activo' => true,
        ]);

        Livewire::test(AppointmentList::class)
            ->assertSeeHtmlInOrder([
                'wire:key="appointment-'.$earlyFirstDay->id.'"',
                'wire:key="appointment-'.$earlyNextDay->id.'"',
                'wire:key="appointment-'.$lateNextDay->id.'"',
            ]);
    }

    public function test_appointment_list_orders_by_client(): void
    {
        $ana = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $luis = Client::query()->create([
            'nombre' => 'Luis',
            'apellidos' => 'Gómez',
            'telefono' => '+34699111222',
        ]);

        $luisAppointment = Appointment::query()->create([
            'client_id' => $luis->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);
        $anaAppointment = Appointment::query()->create([
            'client_id' => $ana->id,
            'fecha' => '2026-07-01',
            'hora' => '09:00',
            'enviado' => false,
            'activo' => true,
        ]);

        Livewire::test(AppointmentList::class)
            ->call('sortByColumn', 'cliente')
            ->assertSeeHtmlInOrder([
                'wire:key="appointment-'.$anaAppointment->id.'"',
                'wire:key="appointment-'.$luisAppointment->id.'"',
            ])
            ->call('sortByColumn', 'cliente')
            ->assertSeeHtmlInOrder([
                'wire:key="appointment-'.$luisAppointment->id.'"',
                'wire:key="appointment-'.$anaAppointment->id.'"',
            ]);
    }

    public function test_appointment_list_filters_with_sent_and_active_toggles(): void
    {
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'activo' => true,
        ]);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-01',
            'hora' => '09:00',
            'enviado' => false,
            'activo' => true,
        ]);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-02',
            'hora' => '12:00',
            'enviado' => true,
            'activo' => false,
        ]);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-03',
            'hora' => '13:00',
            'enviado' => false,
            'activo' => false,
        ]);

        Livewire::test(AppointmentList::class)
            ->assertSee('09:00')
            ->assertDontSee('11:30')
            ->assertDontSee('12:00')
            ->assertDontSee('13:00')
            ->set('filter_enviado', true)
            ->assertSee('11:30')
            ->assertSee('12:00')
            ->assertDontSee('09:00')
            ->assertDontSee('13:00')
            ->set('filter_activo', true)
            ->assertSee('13:00')
            ->assertDontSee('09:00')
            ->assertDontSee('11:30')
            ->assertDontSee('12:00')
            ->assertSet('filter_enviado', false)
            ->assertSet('filter_entregado', false);
    }

    public function test_appointment_list_filters_delivered_appointments(): void
    {
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '09:00',
            'enviado' => false,
            'entregado' => false,
            'activo' => true,
        ]);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-01',
            'hora' => '10:00',
            'enviado' => true,
            'entregado' => false,
            'activo' => true,
        ]);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-02',
            'hora' => '11:00',
            'enviado' => true,
            'entregado' => true,
            'activo' => true,
        ]);

        Livewire::test(AppointmentList::class)
            ->assertSee('Entregadas')
            ->set('filter_entregado', true)
            ->assertSee('11:00')
            ->assertDontSee('09:00')
            ->assertDontSee('10:00')
            ->assertSet('filter_enviado', false)
            ->assertSet('filter_activo', false);
    }

    public function test_appointment_list_hides_whatsapp_columns_when_nothing_has_been_sent(): void
    {
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '09:00',
            'enviado' => false,
            'entregado' => false,
            'activo' => true,
        ]);

        Livewire::test(AppointmentList::class)
            ->assertDontSee('Enviado')
            ->assertDontSee('Fecha envío')
            ->assertDontSee('Entregado')
            ->assertDontSee('Fecha entrega')
            ->assertDontSee('Leído')
            ->assertSee('Pendiente');
    }

    public function test_appointment_list_hides_pending_column_when_everything_is_sent(): void
    {
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '09:00',
            'enviado' => true,
            'entregado' => false,
            'activo' => true,
        ]);

        Livewire::test(AppointmentList::class)
            ->assertDontSee('Pendiente');
    }

    public function test_appointment_form_shows_client_matches_after_one_character(): void
    {
        Client::query()->create([
            'nombre' => 'Lucía',
            'apellidos' => 'Martín',
            'telefono' => '+34666777888',
        ]);

        $component = Livewire::test(AppointmentForm::class)
            ->assertSee('Las coincidencias aparecerán aquí')
            ->assertDontSee('Lucía Martín');

        $this->assertFalse($component->instance()->getHasClientSearchProperty());

        $component->set('filter_nombre', 'L')
            ->assertSee('Lucía Martín')
            ->assertSee('+34666777888');
        $this->assertTrue($component->instance()->getHasClientSearchProperty());
    }
}
