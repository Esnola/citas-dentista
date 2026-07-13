<?php

namespace Tests\Feature;

use App\Livewire\AgendaDay;
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
            ->assertDontSee('Agenda mensual')
            ->assertSeeHtml('href="'.route('agenda.index').'"');

        $this->get(route('agenda.index'))
            ->assertOk()
            ->assertSee('Agenda mensual');
    }

    public function test_agenda_shows_working_days_of_the_current_month(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-13 10:00:00', config('app.timezone')));

        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(AgendaIndex::class)
            ->assertViewHas('calendarWeeks', function ($weeks): bool {
                $days = $weeks->flatMap(fn ($week) => $week);

                return $days->contains(fn (array $day): bool => $day['date']->toDateString() === '2026-07-01' && $day['is_current_month'])
                    && $days->contains(fn (array $day): bool => $day['date']->toDateString() === '2026-07-31' && $day['is_current_month'])
                    && ! $days->contains(fn (array $day): bool => $day['date']->isSunday());
            });
    }

    public function test_agenda_cards_show_client_first_name_and_link_to_day_page(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-13 10:00:00', config('app.timezone')));

        $user = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-15',
            'hora' => '11:20:00',
            'enviado' => false,
            'activo' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(AgendaIndex::class)
            ->assertSee('11:20')
            ->assertSee('Ana')
            ->assertDontSee('Cita programada')
            ->assertDontSee('Pérez')
            ->assertSee(route('agenda.day', '2026-07-15'));
    }

    public function test_agenda_day_page_lists_all_day_appointments_as_cards(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-13 10:00:00', config('app.timezone')));

        $user = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-15',
            'hora' => '11:20:00',
            'enviado' => false,
            'activo' => true,
        ]);

        $this->actingAs($user);

        $this->get(route('agenda.day', '2026-07-15'))
            ->assertOk()
            ->assertSee('Ana Pérez');

        Livewire::test(AgendaDay::class, ['date' => '2026-07-15'])
            ->assertSee('Miércoles, 15 de julio')
            ->assertSee('Ana Pérez')
            ->assertSee('11:20')
            ->assertSee('Sin enviar')
            ->assertSee(route('clients.appointments', $client))
            ->assertSee(route('clients.edit', $client->id));
    }

    public function test_shows_inactive_appointments_with_incidence_badges(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-13 10:00:00', config('app.timezone')));

        $user = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Marta',
            'apellidos' => 'López',
            'telefono' => '+34600111222',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-15',
            'hora' => '13:45:00',
            'enviado' => false,
            'entregado' => false,
            'activo' => false,
        ]);

        $this->actingAs($user);

        Livewire::test(AgendaDay::class, ['date' => '2026-07-15'])
            ->assertSee('Marta López')
            ->assertSee('13:45')
            ->assertDontSee('Desactivada')
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
            ->assertSee('Mensajes fallidos');
    }
}
