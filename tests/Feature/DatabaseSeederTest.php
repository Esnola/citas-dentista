<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Database\Seeders\AppointmentSeeder;
use Database\Seeders\ClientSeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_the_initial_admin_as_first_user(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('clients', 10);
        $this->assertDatabaseCount('appointments', 12);

        $admin = User::query()->firstOrFail();

        $this->assertSame(1, $admin->id);
        $this->assertSame('admin@example.com', $admin->email);
    }

    public function test_client_seeder_populates_clients_without_duplicates(): void
    {
        $this->seed(ClientSeeder::class);
        $this->seed(ClientSeeder::class);

        $this->assertDatabaseCount('clients', 10);

        $client = Client::query()->where('telefono', '+34600123123')->firstOrFail();

        $this->assertSame('Ana', $client->nombre);
        $this->assertSame('Pérez López', $client->apellidos);
    }

    public function test_appointment_seeder_populates_client_appointments_without_duplicates(): void
    {
        $this->seed(ClientSeeder::class);
        $this->seed(AppointmentSeeder::class);
        $this->seed(AppointmentSeeder::class);

        $this->assertDatabaseCount('appointments', 12);

        $client = Client::query()->where('telefono', '+34600123123')->firstOrFail();

        $this->assertSame(2, $client->appointments()->count());

        $appointment = Appointment::query()
            ->whereBelongsTo($client)
            ->whereDate('fecha', '2026-07-01')
            ->where('hora', '09:30')
            ->firstOrFail();

        $this->assertTrue($appointment->activo);
        $this->assertFalse($appointment->enviado);
    }
}
