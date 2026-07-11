<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\SistemaOpcion;
use App\Models\WhatsAppMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PurgePastAppointmentsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_appointments_older_than_the_configured_retention_period(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 7, 11, 12, 0, 0, config('app.timezone')));

        SistemaOpcion::get()->update([
            'retention_period' => '1_week',
        ]);

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        $expiredAppointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => now()->subDays(8)->toDateString(),
            'hora' => '10:00:00',
        ]);

        $recentAppointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => now()->subDays(6)->toDateString(),
            'hora' => '10:00:00',
        ]);

        WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $expiredAppointment->id,
            'nombre' => $client->nombre,
            'apellidos' => $client->apellidos,
            'telefono' => $client->telefono,
            'scheduled_for' => now()->subDays(8),
            'message' => 'Recordatorio',
        ]);

        $expiredAppointment->changes()->create([
            'fecha_anterior' => now()->subDays(9)->toDateString(),
            'hora_anterior' => '09:00:00',
            'fecha_nueva' => now()->subDays(8)->toDateString(),
            'hora_nueva' => '10:00:00',
        ]);

        $this->artisan('appointments:purge-past')
            ->expectsOutput('Borrado 1 citas expiradas.')
            ->assertSuccessful();

        $this->assertDatabaseMissing('appointments', ['id' => $expiredAppointment->id]);
        $this->assertDatabaseMissing('whatsapp_messages', ['appointment_id' => $expiredAppointment->id]);
        $this->assertDatabaseMissing('appointment_changes', ['appointment_id' => $expiredAppointment->id]);
        $this->assertDatabaseHas('appointments', ['id' => $recentAppointment->id]);
    }

    public function test_it_deletes_appointments_on_the_cutoff_day_regardless_of_hour(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 7, 11, 12, 0, 0, config('app.timezone')));

        SistemaOpcion::get()->update([
            'retention_period' => '1_week',
        ]);

        $client = Client::query()->create([
            'nombre' => 'Luis',
            'apellidos' => 'García',
            'telefono' => '+34600999888',
        ]);

        $cutoffAppointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => now()->subDays(7)->toDateString(),
            'hora' => '23:59:59',
        ]);

        $this->artisan('appointments:purge-past')
            ->expectsOutput('Borrado 1 citas expiradas.')
            ->assertSuccessful();

        $this->assertDatabaseMissing('appointments', ['id' => $cutoffAppointment->id]);
    }

    public function test_it_skips_when_cleanup_is_disabled(): void
    {
        SistemaOpcion::get()->update([
            'retention_period' => 'disabled',
        ]);

        $this->artisan('appointments:purge-past')
            ->expectsOutput('Borrado automático desactivado.')
            ->assertSuccessful();
    }
}
