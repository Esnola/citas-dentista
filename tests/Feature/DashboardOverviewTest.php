<?php

namespace Tests\Feature;

use App\Livewire\DashboardOverview;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardOverviewTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_shows_tomorrow_appointments_on_regular_days(): void
    {
        $now = Carbon::parse('2026-06-22 10:00:00')->next(Carbon::FRIDAY);
        Carbon::setTestNow($now);
        $appointmentAt = $now->copy()->addDay()->setTime(11, 20);

        $user = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => $appointmentAt->toDateString(),
            'hora' => $appointmentAt->format('H:i:s'),
            'enviado' => false,
            'activo' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(DashboardOverview::class)
            ->assertSee('Ana Pérez')
            ->assertSee('+34600123123')
            ->assertSee($appointmentAt->format('d/m/Y H:i'));
    }

    public function test_shows_monday_appointments_when_today_is_saturday(): void
    {
        $now = Carbon::parse('2026-06-22 10:00:00')->next(Carbon::SATURDAY);
        Carbon::setTestNow($now);
        $appointmentAt = $now->copy()->next(Carbon::MONDAY)->setTime(9, 0);

        $user = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Lucía',
            'apellidos' => 'Martín',
            'telefono' => '+34666777888',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => $appointmentAt->toDateString(),
            'hora' => $appointmentAt->format('H:i:s'),
            'enviado' => false,
            'activo' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(DashboardOverview::class)
            ->assertSee('Lucía Martín')
            ->assertSee('+34666777888')
            ->assertSee($appointmentAt->format('d/m/Y H:i'));
    }

    public function test_date_buttons_render_with_correct_labels(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(DashboardOverview::class)
            ->assertSee('Mañana')
            ->assertSee('Pasado mañana')
            ->assertSee('En 3 días');
    }

    public function test_selecting_date_offset_updates_appointments(): void
    {
        $now = Carbon::parse('2026-06-29 10:00:00')->next(Carbon::MONDAY);
        Carbon::setTestNow($now);
        $user = User::factory()->create();

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        $twoDaysLater = $now->copy()->addDays(2)->setTime(14, 0);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => $twoDaysLater->toDateString(),
            'hora' => $twoDaysLater->format('H:i:s'),
            'enviado' => false,
            'activo' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(DashboardOverview::class)
            ->assertDontSee('Ana Pérez')
            ->call('selectDate', 2)
            ->assertSee('Ana Pérez');
    }

    public function test_sunday_skip_when_tomorrow_is_sunday(): void
    {
        $now = Carbon::parse('2026-06-27 10:00:00');
        Carbon::setLocale('es');
        Carbon::setTestNow($now);
        $user = User::factory()->create();

        $client = Client::query()->create([
            'nombre' => 'Lucía',
            'apellidos' => 'Martín',
            'telefono' => '+34666777888',
        ]);

        $monday = $now->copy()->next(Carbon::MONDAY)->setTime(9, 0);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => $monday->toDateString(),
            'hora' => $monday->format('H:i:s'),
            'enviado' => false,
            'activo' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(DashboardOverview::class)
            ->assertSee('Lucía Martín')
            ->assertSee('lunes');
    }

    public function test_sunday_warning_not_shown_on_regular_days(): void
    {
        $now = Carbon::parse('2026-06-29 10:00:00');
        Carbon::setTestNow($now);
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(DashboardOverview::class)
            ->assertDontSee('domingo');
    }
}
