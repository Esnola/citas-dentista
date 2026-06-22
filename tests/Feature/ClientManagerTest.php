<?php

namespace Tests\Feature;

use App\Livewire\ClientManager;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ClientManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_clients_screen_filters_and_updates_clients(): void
    {
        Carbon::setTestNow('2026-06-22 10:00:00');

        Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '600123123',
        ]);

        Carbon::setTestNow('2026-06-23 11:15:00');

        Client::query()->create([
            'nombre' => 'Luis',
            'apellidos' => 'Gómez',
            'telefono' => '699999999',
        ]);

        Livewire::test(ClientManager::class)
            ->set('filter_nombre', 'Ana')
            ->assertSee('Ana Pérez')
            ->assertDontSee('Luis Gómez');

        Carbon::setTestNow();
    }

    public function test_clients_screen_can_edit_selected_client(): void
    {
        Carbon::setTestNow('2026-06-22 10:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '600123123',
        ]);

        Livewire::test(ClientManager::class)
            ->call('selectClient', $client->id)
            ->set('nombre', 'Ana Maria')
            ->set('apellidos', 'Pérez López')
            ->set('telefono', '611222333')
            ->call('save')
            ->assertSee('Cliente actualizado correctamente.');

        $client->refresh();

        $this->assertSame('Ana Maria', $client->nombre);
        $this->assertSame('Pérez López', $client->apellidos);
        $this->assertSame('+34611222333', $client->telefono);
        $this->assertSame('2026-06-22', $client->created_at->toDateString());

        Carbon::setTestNow();
    }

    public function test_clients_page_can_open_selected_client_from_query_string(): void
    {
        $admin = \App\Models\User::factory()->create();
        Carbon::setTestNow('2026-06-25 12:40:00');
        $client = Client::query()->create([
            'nombre' => 'Lucía',
            'apellidos' => 'Martín',
            'telefono' => '+34666777888',
        ]);

        $this->actingAs($admin)
            ->get(route('clients.index', ['client' => $client->id]))
            ->assertOk()
            ->assertSee('Lucía Martín')
            ->assertSee('Editar cliente')
            ->assertSee('25/06/2026 12:40');

        Carbon::setTestNow();
    }
}
