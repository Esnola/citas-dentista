<?php

namespace Tests\Feature;

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

class FailedWhatsAppMessageDisplayTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_failed_message_shows_error_in_appointment_list(): void
    {
        Carbon::setTestNow('2026-06-30 10:00:00');

        $user = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-05',
            'hora' => '11:00:00',
            'enviado' => true,
            'activo' => true,
        ]);

        WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
            'scheduled_for' => now()->subHour(),
            'message' => 'Hola Ana',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_FAILED,
            'last_error' => 'Twilio returned error',
        ]);

        $this->actingAs($user);

        Livewire::test(AppointmentList::class, ['clientId' => $client->id])
            ->assertSee('Error de envío')
            ->assertDontSee('En cola');
    }

    public function test_failed_message_shows_no_entregado_not_green_in_delivered_column(): void
    {
        Carbon::setTestNow('2026-06-30 10:00:00');

        $user = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-05',
            'hora' => '11:00:00',
            'enviado' => true,
            'whatsapp_sent_at' => now()->subHour(),
            'activo' => true,
        ]);

        WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
            'scheduled_for' => now()->subHour(),
            'message' => 'Hola Ana',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_FAILED,
            'provider_message_id' => 'SM_FAILED_123',
        ]);

        $this->actingAs($user);

        Livewire::test(AppointmentList::class, ['clientId' => $client->id])
            ->set('showAllHistory', true)
            ->assertSee('No entregado')
            ->assertSee('text-green-400');
    }

    public function test_successful_message_shows_green_check(): void
    {
        Carbon::setTestNow('2026-06-30 10:00:00');

        $user = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-05',
            'hora' => '11:00:00',
            'enviado' => true,
            'entregado' => true,
            'whatsapp_sent_at' => now()->subHour(),
            'whatsapp_delivered_at' => now()->subMinutes(30),
            'activo' => true,
        ]);

        WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
            'scheduled_for' => now()->subHour(),
            'message' => 'Hola Ana',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_SENT,
            'sent_at' => now()->subHour(),
            'provider_message_id' => 'SM_SENT_123',
        ]);

        $this->actingAs($user);

        Livewire::test(AppointmentList::class, ['clientId' => $client->id])
            ->assertSee('text-green-400')
            ->assertDontSee('Error de envío');
    }

    public function test_dispatch_does_not_mark_enviadoo_on_provider_failure(): void
    {
        Carbon::setTestNow('2026-06-30 12:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '600123123',
        ]);

        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-05',
            'hora' => '11:00:00',
            'enviado' => false,
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
            'status' => WhatsAppMessage::STATUS_PENDING,
        ]);

        Config::set('whatsapp.driver', 'twilio');
        Config::set('whatsapp.message_mode', 'text');
        Config::set('whatsapp.twilio.account_sid', 'AC123');
        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.mode', 'sandbox');
        Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');

        Http::fake([
            'api.twilio.com/*/Messages.json' => Http::response([
                'sid' => 'SMFAILED456',
                'status' => 'failed',
            ], 201),
        ]);

        $this->artisan('whatsapp:dispatch-due')->assertExitCode(0);

        $appointment->refresh();
        $message = WhatsAppMessage::query()->firstOrFail();

        $this->assertFalse($appointment->enviado, 'Appointment should NOT be marked as enviado when provider reports failure');
        $this->assertSame(WhatsAppMessage::STATUS_FAILED, $message->status);
        $this->assertNull($message->sent_at);
        $this->assertSame('SMFAILED456', $message->provider_message_id);

        Carbon::setTestNow();
    }

    public function test_message_with_failed_payload_shows_error_in_delivered_column(): void
    {
        Carbon::setTestNow('2026-06-30 10:00:00');

        $user = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-05',
            'hora' => '11:00:00',
            'enviado' => true,
            'activo' => true,
        ]);

        WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
            'scheduled_for' => now()->subHour(),
            'message' => 'Hola Ana',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_SENT,
            'sent_at' => now()->subHour(),
            'provider_message_id' => 'SM_PROVIDER_123',
            'provider_payload' => [
                'provider' => 'twilio',
                'raw' => [
                    'status' => 'failed',
                ],
            ],
        ]);

        $this->actingAs($user);

        Livewire::test(AppointmentList::class, ['clientId' => $client->id])
            ->set('showAllHistory', true)
            ->assertSee('No entregado');
    }
}
