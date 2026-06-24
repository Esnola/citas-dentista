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
use Livewire\Livewire;
use Tests\TestCase;

class AppointmentManagerTest extends TestCase
{
    use RefreshDatabase;

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
            'activo' => 1,
        ]);

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
            ->assertSee('Estado activo actualizado.');

        $this->assertFalse($appointment->refresh()->activo);
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
            'client_id' => $secondClient->id,
            'fecha' => '2026-07-01',
            'hora' => '09:00',
            'enviado' => false,
            'activo' => true,
        ]);

        Livewire::withQueryParams(['client' => $firstClient->id])
            ->test(AppointmentList::class)
            ->assertSee('Citas de Ana Pérez')
            ->assertSee('11:30')
            ->assertDontSee('Luis Gómez')
            ->assertDontSee('09:00');
    }

    public function test_active_filter_excludes_past_and_sent_appointments(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        $futureActiveAppointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-01',
            'hora' => '11:30',
            'enviado' => false,
            'activo' => true,
        ]);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '12:30',
            'enviado' => true,
            'activo' => true,
        ]);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '13:30',
            'enviado' => false,
            'activo' => false,
        ]);

        Livewire::test(AppointmentList::class)
            ->set('filter_activo', true)
            ->assertSee('11:30')
            ->assertSeeHtml('wire:key="appointment-'.$futureActiveAppointment->id.'"')
            ->assertDontSee('2026-06-01')
            ->assertDontSee('12:30')
            ->assertDontSee('13:30');

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
            ->assertSee('Estado activo actualizado.');

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
            ->assertSeeHtml('bg-slate-900/50 text-slate-400')
            ->assertSeeHtml('aria-label="Eliminar cita"')
            ->assertDontSeeHtml('appointments/'.$appointment->id.'/edit');

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
            ->call('deleteConfirmed')
            ->assertSee('Cita eliminada correctamente.');

        $this->assertDatabaseMissing('appointments', [
            'id' => $appointment->id,
        ]);
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
            ->set('selectedAppointmentId', $appointment->id)
            ->set('selectedClientId', $client->id)
            ->set('fecha', '2026-06-30')
            ->set('hora', '11:30')
            ->set('enviado', false)
            ->set('activo', false)
            ->call('save')
            ->assertSee('Cita actualizada correctamente.');

        $this->assertFalse($appointment->refresh()->activo);

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

        Livewire::test(AppointmentList::class)
            ->set('filter_enviado', true)
            ->assertSee('11:30')
            ->assertSee('12:00')
            ->assertDontSee('09:00')
            ->set('filter_activo', true)
            ->assertSee('11:30')
            ->assertDontSee('12:00')
            ->assertDontSee('09:00');
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
