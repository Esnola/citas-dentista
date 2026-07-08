<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResetDatabaseAndSeedCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_target_tables_and_runs_the_database_seeder(): void
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

        $this->artisan('db:reset-and-seed --force')
            ->expectsOutput('Deleted 1 user(s), 1 client(s), 1 WhatsApp message(s) and 1 appointment(s).')
            ->expectsOutput('DatabaseSeeder executed.')
            ->assertExitCode(0);

        $this->assertSame(2, User::query()->count());
        $this->assertSame(10, Client::query()->count());
        $this->assertSame(205, Appointment::query()->count());
        $this->assertSame(0, WhatsAppMessage::query()->count());
    }

    public function test_it_requires_force(): void
    {
        $this->artisan('db:reset-and-seed')
            ->expectsOutput('This command is destructive. Re-run with --force.')
            ->assertExitCode(1);
    }
}
