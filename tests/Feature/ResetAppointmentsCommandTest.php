<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResetAppointmentsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_appointments_and_whatsapp_messages_then_runs_the_appointment_seeder(): void
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

        Client::query()->create([
            'nombre' => 'Luis',
            'apellidos' => 'Pérez',
            'telefono' => '+34 611 111 111',
        ]);

        $this->artisan('appointments:reset --force')
            ->expectsOutput('Deleted 1 WhatsApp message(s) and 1 appointment(s).')
            ->expectsOutput('AppointmentSeeder executed.')
            ->assertExitCode(0);

        $this->assertSame(22, Appointment::query()->count());
        $this->assertSame(0, WhatsAppMessage::query()->count());
    }

    public function test_it_requires_force(): void
    {
        $this->artisan('appointments:reset')
            ->expectsOutput('This command is destructive. Re-run with --force.')
            ->assertExitCode(1);
    }
}
