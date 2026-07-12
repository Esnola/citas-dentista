<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\AppointmentReminderPreference;
use App\Models\AppSetting;
use App\Models\Client;
use App\Models\TwilioContentTemplate;
use App\Models\User;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppSenderNumber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResetClientDataCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_and_restarts_all_non_protected_tables(): void
    {
        $user = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'López',
            'telefono' => '+34 600 000 000',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => today()->toDateString(),
            'hora' => '10:30:00',
            'enviado' => false,
            'activo' => true,
        ]);
        WhatsAppMessage::query()->create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'López',
            'telefono' => '+34 600 000 000',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Hola',
            'source' => WhatsAppMessage::SOURCE_MANUAL,
            'status' => WhatsAppMessage::STATUS_PENDING,
        ]);
        AppointmentReminderPreference::query()->create([
            'channel' => 'whatsapp',
            'lead_days' => 1,
            'enabled' => true,
        ]);
        $credential = WhatsAppCredential::query()->create(['mode' => 'sandbox']);
        WhatsAppSenderNumber::query()->create([
            'whatsapp_credential_id' => $credential->id,
            'number' => '15551234567',
        ]);
        TwilioContentTemplate::query()->create([
            'nombre' => 'Recordatorio',
            'content_sid' => 'HX'.str_repeat('1', 32),
            'seleccionada' => true,
        ]);
        $userCount = User::query()->count();

        $this->artisan('clients:reset-data --force')
            ->expectsOutput('ClientSeeder and AppointmentSeeder executed.')
            ->expectsOutput('Protected tables were not changed.')
            ->assertExitCode(0);

        $this->assertSame(10, Client::query()->count());
        $this->assertSame(205, Appointment::query()->count());
        $this->assertSame(1, WhatsAppMessage::query()->count());
        $this->assertSame($userCount, User::query()->count());
        $this->assertSame(1, AppointmentReminderPreference::query()->where('channel', 'whatsapp')->count());
        $this->assertSame(1, AppSetting::query()->where('dispatch_enabled', true)->count());
        $this->assertSame(1, WhatsAppCredential::query()->whereKey($credential->id)->count());
        $this->assertSame(0, WhatsAppSenderNumber::query()->where('whatsapp_credential_id', $credential->id)->count());
        $this->assertSame(1, TwilioContentTemplate::query()->where('content_sid', 'HX'.str_repeat('1', 32))->count());
    }

    public function test_it_requires_force(): void
    {
        $this->artisan('clients:reset-data')
            ->expectsOutput('This command is destructive. Re-run with --force.')
            ->assertExitCode(1);
    }
}
