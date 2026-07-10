<?php

namespace Tests\Feature;

use App\Livewire\AgendaIndex;
use App\Livewire\DashboardOverview;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
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

    public function test_agenda_has_its_own_page_and_sidebar_link(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee('Agenda del día')
            ->assertSeeHtml('href="'.route('agenda.index').'"');

        $this->get(route('agenda.index'))
            ->assertOk()
            ->assertSee('Agenda del día');
    }

    public function test_shows_today_appointments_by_default(): void
    {
        $now = Carbon::parse('2026-06-22 10:00:00')->next(Carbon::FRIDAY);
        Carbon::setTestNow($now);
        $appointmentAt = $now->copy()->setTime(11, 20);

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

        Livewire::test(AgendaIndex::class)
            ->assertSee('Ana Pérez')
            ->assertSee('11:20');
    }

    public function test_shows_saturday_appointments_when_today_is_saturday(): void
    {
        $now = Carbon::parse('2026-06-22 10:00:00')->next(Carbon::SATURDAY);
        Carbon::setTestNow($now);
        $appointmentAt = $now->copy()->setTime(9, 0);

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

        Livewire::test(AgendaIndex::class)
            ->assertSee('Lucía Martín')
            ->assertSee('09:00');
    }

    public function test_date_buttons_render_with_correct_labels(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(AgendaIndex::class)
            ->assertSee('Hoy')
            ->assertSee('Mañana')
            ->assertSee('En 2 días')
            ->assertSee('En 10 días')
            ->assertDontSee('Pasado mañana');
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

        // Appointment in 2 days (Wednesday)
        $twoDaysLater = $now->copy()->addDays(2)->setTime(14, 0);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => $twoDaysLater->toDateString(),
            'hora' => $twoDaysLater->format('H:i:s'),
            'enviado' => false,
            'activo' => true,
        ]);

        $this->actingAs($user);

        // Default is today (Monday) — appointment not shown
        Livewire::test(AgendaIndex::class)
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

        // Today is Saturday — create appointment for today (default view)
        $saturday = $now->copy()->setTime(9, 0);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => $saturday->toDateString(),
            'hora' => $saturday->format('H:i:s'),
            'enviado' => false,
            'activo' => true,
        ]);

        // Also create Monday appointment to verify skip works when selecting tomorrow
        $monday = $now->copy()->next(Carbon::MONDAY)->setTime(9, 0);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => $monday->toDateString(),
            'hora' => $monday->format('H:i:s'),
            'enviado' => false,
            'activo' => true,
        ]);

        $this->actingAs($user);

        // Default view shows today (Saturday)
        Livewire::test(AgendaIndex::class)
            ->assertSee('Lucía Martín');

        // Selecting tomorrow (offset 1) skips Sunday → shows Monday
        Livewire::test(AgendaIndex::class)
            ->call('selectDate', 1)
            ->assertSee('lunes');
    }

    public function test_sunday_warning_not_shown_on_regular_days(): void
    {
        $now = Carbon::parse('2026-06-29 10:00:00');
        Carbon::setTestNow($now);
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(AgendaIndex::class)
            ->assertDontSee('domingo');
    }

    public function test_sunday_warning_shown_when_offset_lands_on_sunday(): void
    {
        $now = Carbon::parse('2026-06-27 10:00:00');
        Carbon::setTestNow($now);
        $user = User::factory()->create();

        $this->actingAs($user);

        // Default is today (Saturday) — no warning
        Livewire::test(AgendaIndex::class)
            ->assertDontSee('domingo');

        // Selecting tomorrow (offset 1) lands on Sunday — warning shown
        Livewire::test(AgendaIndex::class)
            ->call('selectDate', 1)
            ->assertSee('domingo');
    }

    public function test_client_name_links_to_appointments(): void
    {
        $now = Carbon::parse('2026-06-29 10:00:00')->next(Carbon::FRIDAY);
        Carbon::setTestNow($now);
        $user = User::factory()->create();

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        // Create appointment for today (default view)
        $appointmentAt = $now->copy()->setTime(11, 20);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => $appointmentAt->toDateString(),
            'hora' => $appointmentAt->format('H:i:s'),
            'enviado' => false,
            'activo' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(AgendaIndex::class)
            ->assertSee(route('clients.appointments', $client))
            ->assertSee(route('clients.edit', $client->id));
    }

    public function test_edit_client_button_present(): void
    {
        $now = Carbon::parse('2026-06-29 10:00:00')->next(Carbon::FRIDAY);
        Carbon::setTestNow($now);
        $user = User::factory()->create();

        $client = Client::query()->create([
            'nombre' => 'Carlos',
            'apellidos' => 'Ruiz',
            'telefono' => '+34611222333',
        ]);

        // Create appointment for today (default view)
        $appointmentAt = $now->copy()->setTime(9, 0);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => $appointmentAt->toDateString(),
            'hora' => $appointmentAt->format('H:i:s'),
            'enviado' => false,
            'activo' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(AgendaIndex::class)
            ->assertSee('Carlos Ruiz')
            ->assertSee('09:00');
    }

    public function test_shows_inactive_appointments_with_incidence_badges(): void
    {
        $now = Carbon::parse('2026-06-29 10:00:00')->next(Carbon::FRIDAY);
        Carbon::setTestNow($now);
        $appointmentAt = $now->copy()->setTime(13, 45);

        $user = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Marta',
            'apellidos' => 'López',
            'telefono' => '+34600111222',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => $appointmentAt->toDateString(),
            'hora' => $appointmentAt->format('H:i:s'),
            'enviado' => false,
            'entregado' => false,
            'activo' => false,
        ]);

        $this->actingAs($user);

        Livewire::test(AgendaIndex::class)
            ->assertSee('Marta López')
            ->assertSee('13:45')
            ->assertSee('Desactivada')
            ->assertSee('Sin enviar');
    }

    public function test_dashboard_shows_operational_summary_and_only_upcoming_active_appointments(): void
    {
        $now = Carbon::parse('2026-07-10 10:00:00', config('app.timezone'));
        Carbon::setTestNow($now);
        $user = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        foreach ([
            ['hora' => '09:00:00', 'activo' => true],
            ['hora' => '11:30:00', 'activo' => true],
            ['hora' => '12:30:00', 'activo' => false],
        ] as $appointment) {
            Appointment::query()->create([
                'client_id' => $client->id,
                'fecha' => $now->toDateString(),
                'hora' => $appointment['hora'],
                'activo' => $appointment['activo'],
            ]);
        }

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => $now->copy()->addDay()->toDateString(),
            'hora' => '08:45:00',
            'activo' => true,
            'whatsapp_sent_at' => $now,
            'enviado' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(DashboardOverview::class)
            ->assertViewHas('todayCount', 2)
            ->assertViewHas('upcomingWithoutReminderCount', 1)
            ->assertViewHas('nextAppointment', fn (Appointment $appointment): bool => $appointment->hora === '11:30:00')
            ->assertViewHas('nextAppointments', fn ($appointments): bool => $appointments->count() === 2)
            ->assertSee('11:30')
            ->assertDontSee('09:00')
            ->assertDontSee('12:30');
    }

    public function test_dashboard_incidents_exclude_inbound_whatsapp_messages(): void
    {
        $now = Carbon::parse('2026-07-10 10:00:00', config('app.timezone'));
        Carbon::setTestNow($now);
        $user = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Lucía',
            'apellidos' => 'Martín',
            'telefono' => '+34666777888',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => $now->copy()->addDay()->toDateString(),
            'hora' => '09:00:00',
            'activo' => true,
            'pendiente_reprogramacion' => true,
        ]);

        foreach ([WhatsAppMessage::DIRECTION_OUTBOUND, WhatsAppMessage::DIRECTION_INBOUND] as $direction) {
            WhatsAppMessage::query()->create([
                'user_id' => $user->id,
                'client_id' => $client->id,
                'appointment_id' => $appointment->id,
                'nombre' => $client->nombre,
                'apellidos' => $client->apellidos,
                'telefono' => $client->telefono,
                'scheduled_for' => $now,
                'message' => 'Recordatorio',
                'status' => WhatsAppMessage::STATUS_FAILED,
                'direction' => $direction,
            ]);
        }

        $this->actingAs($user);

        Livewire::test(DashboardOverview::class)
            ->assertViewHas('failedCount', 1)
            ->assertViewHas('rescheduleCount', 1)
            ->assertSee('1 mensajes fallidos')
            ->assertSee('1 por reprogramar');
    }
}
