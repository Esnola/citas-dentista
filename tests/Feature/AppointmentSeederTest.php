<?php

namespace Tests\Feature;

use App\Models\Appointment;
use Carbon\Carbon;
use Database\Seeders\AppointmentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_it_creates_three_hundred_thirty_appointments_with_unique_dates_per_phone(): void
    {
        Carbon::setTestNow('2026-06-29 10:00:00');

        $this->seed(AppointmentSeeder::class);
        $this->seed(AppointmentSeeder::class);

        $this->assertDatabaseCount('clients', 30);
        $this->assertDatabaseCount('appointments', 330);

        $appointments = Appointment::query()
            ->with('client')
            ->get();

        $this->assertCount(330, $appointments);

        $startDate = now()->toDateString();
        $endDate = now()->addDays(10)->toDateString();

        foreach ($appointments as $appointment) {
            $this->assertNotNull($appointment->client);
            $this->assertGreaterThanOrEqual($startDate, $appointment->fecha->toDateString());
            $this->assertLessThanOrEqual($endDate, $appointment->fecha->toDateString());
        }

        foreach ($appointments->groupBy(fn (Appointment $appointment): string => (string) $appointment->client?->telefono) as $phoneAppointments) {
            $dates = $phoneAppointments->pluck('fecha')->map(fn (Carbon $date): string => $date->toDateString())->all();

            $this->assertCount(count(array_unique($dates)), $dates);
        }
    }
}
