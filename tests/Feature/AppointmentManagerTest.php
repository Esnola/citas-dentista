<?php

namespace Tests\Feature;

use App\Livewire\AppointmentManager;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AppointmentManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_appointment_manager_can_create_an_appointment_for_a_client(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);

        Livewire::test(AppointmentManager::class)
            ->set('selectedClientId', $client->id)
            ->set('fecha', '2026-06-30')
            ->set('hora', '11:30')
            ->set('enviado', false)
            ->set('activo', true)
            ->call('save')
            ->assertSee('Cita creada correctamente.');

        $this->assertDatabaseHas('appointments', [
            'client_id' => $client->id,
            'fecha' => '2026-06-30 00:00:00',
            'hora' => '11:30',
            'enviado' => 0,
            'activo' => 1,
        ]);

        Carbon::setTestNow();
    }

    public function test_appointment_page_can_open_selected_client_from_query_string(): void
    {
        $user = User::factory()->create();

        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Lucía',
            'apellidos' => 'Martín',
            'telefono' => '600123123',
        ]);

        $this->actingAs($user)
            ->get(route('appointments.index', ['client' => $client->id]))
            ->assertOk()
            ->assertSee('Lucía Martín')
            ->assertSee('Alta: 23/06/2026 09:00');

        Carbon::setTestNow();
    }
}
