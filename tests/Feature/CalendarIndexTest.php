<?php

namespace Tests\Feature;

use App\Livewire\CalendarIndex;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CalendarIndexTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_calendar_page_renders(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('calendar.index'))
            ->assertOk()
            ->assertSee('Calendario');
    }

    public function test_calendar_shows_monthly_appointment_counts(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-13 10:00:00', config('app.timezone')));

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-15',
            'hora' => '09:00:00',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-15',
            'hora' => '12:00:00',
            'cita_activa' => false,
        ]);

        Livewire::test(CalendarIndex::class)
            ->assertViewHas('calendarWeeks', function ($weeks): bool {
                $day = $weeks
                    ->flatMap(fn ($week) => $week)
                    ->filter()
                    ->first(fn (array $day): bool => $day['date']->toDateString() === '2026-07-15');

                return (int) data_get($day, 'appointments_count') === 2
                    && (int) data_get($day, 'inactive_appointments_count') === 1;
            });
    }

    public function test_calendar_hides_sunday_appointment_counts(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-13 10:00:00', config('app.timezone')));

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-19',
            'hora' => '09:00:00',
            'cita_activa' => false,
        ]);

        Livewire::test(CalendarIndex::class)
            ->assertViewHas('calendarWeeks', function ($weeks): bool {
                $day = $weeks
                    ->flatMap(fn ($week) => $week)
                    ->filter()
                    ->first(fn (array $day): bool => $day['date']->toDateString() === '2026-07-19');

                return data_get($day, 'is_sunday') === true
                    && (int) data_get($day, 'appointments_count') === 0
                    && (int) data_get($day, 'inactive_appointments_count') === 0;
            });
    }
}
