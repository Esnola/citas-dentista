<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\AppointmentReminderPreference;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppDispatchCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_due_messages_are_sent_via_cloud_api_and_marked_as_sent(): void
    {
        $admin = User::factory()->create();

        WhatsAppMessage::query()->create([
            'user_id' => $admin->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '600123123',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Hola Ana',
            'source' => WhatsAppMessage::SOURCE_MANUAL,
            'status' => WhatsAppMessage::STATUS_PENDING,
        ]);

        Config::set('whatsapp.driver', 'cloud_api');
        Config::set('whatsapp.cloud_api.base_url', 'https://graph.facebook.com');
        Config::set('whatsapp.cloud_api.version', 'v22.0');
        Config::set('whatsapp.cloud_api.phone_number_id', '1234567890');
        Config::set('whatsapp.cloud_api.access_token', 'test-token');
        Config::set('whatsapp.default_country_code', '+34');

        Http::fake([
            'graph.facebook.com/*/messages' => Http::response([
                'messages' => [
                    ['id' => 'wamid.TEST123'],
                ],
            ], 200),
        ]);

        $this->artisan('whatsapp:dispatch-due')->assertExitCode(0);

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://graph.facebook.com/v22.0/1234567890/messages'
                && $request['messaging_product'] === 'whatsapp'
                && $request['to'] === '+34600123123'
                && $request['type'] === 'text';
        });

        $message = WhatsAppMessage::query()->firstOrFail();

        $this->assertSame(WhatsAppMessage::STATUS_SENT, $message->status);
        $this->assertSame('wamid.TEST123', $message->provider_message_id);
        $this->assertSame('cloud_api', $message->provider_payload['provider']);
        $this->assertSame('Hola Ana', $message->provider_payload['payload']['text']['body']);
        $this->assertNotNull($message->sent_at);
    }

    public function test_active_unsent_due_appointments_are_queued_sent_and_marked_as_sent(): void
    {
        Carbon::setTestNow('2026-06-23 12:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '600123123',
        ]);

        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-23',
            'hora' => '11:45',
            'enviado' => false,
            'activo' => true,
            'cita_activa' => true,
        ]);

        Config::set('whatsapp.driver', 'twilio');
        Config::set('whatsapp.message_mode', 'text');
        Config::set('whatsapp.twilio.account_sid', 'AC123');
        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.mode', 'sandbox');
        Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');
        Config::set('whatsapp.default_country_code', '+34');

        Http::fake([
            'api.twilio.com/*/Messages.json' => Http::response([
                'sid' => 'SMDISPATCHDUE123',
                'status' => 'queued',
            ], 201),
            'api.twilio.com/*/Messages/SMDISPATCHDUE123.json' => Http::response([
                'sid' => 'SMDISPATCHDUE123',
                'status' => 'delivered',
            ], 200),
        ]);

        $this->artisan('whatsapp:dispatch-due')
            ->expectsOutput('Queued 1 appointment message(s).')
            ->expectsOutput('Processed 1 due message(s).')
            ->assertExitCode(0);

        $message = WhatsAppMessage::query()->firstOrFail();

        $this->assertSame($appointment->id, $message->appointment_id);
        $this->assertSame($client->id, $message->client_id);
        $this->assertSame(WhatsAppMessage::SOURCE_APPOINTMENT, $message->source);
        $this->assertSame(WhatsAppMessage::STATUS_SENT, $message->status);
        $this->assertSame('SMDISPATCHDUE123', $message->provider_message_id);
        $this->assertSame(1, $message->metadata['lead_days']);
        $appointment->refresh();

        $this->assertTrue($appointment->enviado);
        $this->assertFalse($appointment->activo);
        $this->assertTrue($appointment->cita_activa);
        $this->assertTrue($appointment->entregado);
        $this->assertNotNull($appointment->refresh()->whatsapp_sent_at);
        $this->assertNotNull($appointment->whatsapp_delivered_at);

        Http::assertSent(function ($request): bool {
            return $request->method() === 'GET'
                && $request->url() === 'https://api.twilio.com/2010-04-01/Accounts/AC123/Messages/SMDISPATCHDUE123.json';
        });

        $this->artisan('whatsapp:dispatch-due')
            ->expectsOutput('Queued 0 appointment message(s).')
            ->expectsOutput('Processed 0 due message(s).')
            ->assertExitCode(0);

        $this->assertSame(1, WhatsAppMessage::query()->count());

        Carbon::setTestNow();
    }

    public function test_active_appointments_are_queued_for_selected_whatsapp_lead_days(): void
    {
        Carbon::setTestNow('2026-06-22 12:00:00');

        AppointmentReminderPreference::saveSelections([
            AppointmentReminderPreference::CHANNEL_WHATSAPP => [1, 2, 7],
            AppointmentReminderPreference::CHANNEL_EMAIL => [3],
        ]);

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '600123123',
        ]);

        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:45',
            'enviado' => false,
            'activo' => true,
        ]);

        Config::set('whatsapp.driver', 'log');

        $this->artisan('whatsapp:dispatch-due')
            ->expectsOutput('Queued 3 appointment message(s).')
            ->expectsOutput('Processed 0 due message(s).')
            ->assertExitCode(0);

        $this->assertSame(3, WhatsAppMessage::query()->where('appointment_id', $appointment->id)->count());

        $messages = WhatsAppMessage::query()
            ->where('appointment_id', $appointment->id)
            ->orderBy('scheduled_for')
            ->get();

        $this->assertSame([7, 2, 1], $messages->pluck('metadata')->map(fn (array $metadata): int => $metadata['lead_days'])->all());
        $this->assertSame([
            '2026-06-23 11:45:00',
            '2026-06-28 11:45:00',
            '2026-06-29 11:45:00',
        ], $messages->map(fn (WhatsAppMessage $message): string => $message->scheduled_for->toDateTimeString())->all());

        Carbon::setTestNow('2026-06-23 12:00:00');

        $this->artisan('whatsapp:dispatch-due')
            ->expectsOutput('Queued 0 appointment message(s).')
            ->expectsOutput('Processed 1 due message(s).')
            ->assertExitCode(0);

        $this->assertSame(3, WhatsAppMessage::query()->where('appointment_id', $appointment->id)->count());
        $this->assertSame(1, WhatsAppMessage::query()->where('status', WhatsAppMessage::STATUS_SENT)->count());
        $this->assertTrue($appointment->refresh()->enviado);
        $this->assertFalse($appointment->entregado);

        Carbon::setTestNow();
    }

    public function test_delivery_sync_command_marks_appointments_as_delivered_when_logs_show_delivered(): void
    {
        Carbon::setTestNow('2026-06-23 12:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '600123123',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:45',
            'enviado' => true,
            'entregado' => false,
            'activo' => true,
        ]);

        WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '600123123',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Hola Ana',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_SENT,
            'provider_message_id' => 'SMLOG123',
            'provider_payload' => [
                'provider' => 'twilio',
                'raw' => [
                    'status' => 'delivered',
                ],
            ],
        ]);

        $this->artisan('whatsapp:sync-delivery-status')
            ->expectsOutput('Synced 1 delivered appointment(s).')
            ->assertExitCode(0);

        $this->assertTrue($appointment->refresh()->entregado);
        $this->assertNotNull($appointment->whatsapp_delivered_at);

        Carbon::setTestNow();
    }

    public function test_backfill_command_populates_appointment_delivery_timestamps_from_stored_messages(): void
    {
        Carbon::setTestNow('2026-06-23 12:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '600123123',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:45',
            'enviado' => false,
            'entregado' => false,
            'activo' => true,
        ]);

        WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '600123123',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Hola Ana',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_SENT,
            'sent_at' => '2026-06-23 08:05:00',
            'provider_message_id' => 'SMBACKFILL123',
            'provider_payload' => [
                'provider' => 'twilio',
                'raw' => [
                    'status' => 'delivered',
                ],
                'callback' => [
                    'message_status' => 'read',
                    'event_type' => 'READ',
                    'received_at' => '2026-06-23 08:12:00',
                    'payload' => [],
                ],
            ],
        ]);

        $this->artisan('whatsapp:backfill-appointment-delivery-state')
            ->expectsOutput('Backfilled 1 appointment(s).')
            ->assertExitCode(0);

        $appointment->refresh();

        $this->assertTrue($appointment->enviado);
        $this->assertTrue($appointment->entregado);
        $this->assertSame('2026-06-23 08:05:00', $appointment->whatsapp_sent_at?->toDateTimeString());
        $this->assertSame('2026-06-23 08:12:00', $appointment->whatsapp_delivered_at?->toDateTimeString());
        $this->assertSame('2026-06-23 08:12:00', $appointment->whatsapp_read_at?->toDateTimeString());

        Carbon::setTestNow();
    }
}
