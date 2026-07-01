<?php

namespace Tests\Feature;

use App\Livewire\ClientCsvImporter;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;

class ClientCsvImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_import_two_different_clients_with_same_phone(): void
    {
        $admin = User::factory()->create();
        $csv = <<<'CSV'
nombre,apellidos,telefono
Ana,Pérez,600123123
Ana María,Pérez López,600123123
CSV;

        $this->actingAs($admin);

        Livewire::test(ClientCsvImporter::class)
            ->set('file', UploadedFile::fake()->createWithContent('clientes.csv', $csv))
            ->call('import')
            ->assertSet('status', 'Se importaron 2 cliente(s) nuevo(s) correctamente.');

        $this->assertSame(2, Client::query()->count());
        $this->assertSame(
            ['Ana', 'Ana María'],
            Client::query()->orderBy('nombre')->pluck('nombre')->all()
        );
        $this->assertSame(
            ['600123123'],
            Client::query()->pluck('telefono')->unique()->values()->all()
        );
    }

    public function test_admin_can_import_duplicate_same_client_without_creating_extra_rows(): void
    {
        $admin = User::factory()->create();
        $csv = <<<'CSV'
nombre;apellidos;telefono
Ana;Pérez;600123123
Ana;Pérez;600123123
CSV;

        $this->actingAs($admin);

        Livewire::test(ClientCsvImporter::class)
            ->set('file', UploadedFile::fake()->createWithContent('clientes.csv', $csv))
            ->call('import')
            ->assertSet('status', 'Se importaron 1 cliente(s) nuevo(s) correctamente.');

        $this->assertSame(1, Client::query()->count());

        $client = Client::query()->firstOrFail();

        $this->assertSame('Ana', $client->nombre);
        $this->assertSame('Pérez', $client->apellidos);
        $this->assertSame('600123123', $client->telefono);
    }

    public function test_admin_can_import_without_overwriting_existing_client(): void
    {
        $admin = User::factory()->create();

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        $csv = <<<'CSV'
nombre;apellidos;telefono
Ana;Pérez;600123123
CSV;

        $this->actingAs($admin);

        Livewire::test(ClientCsvImporter::class)
            ->set('file', UploadedFile::fake()->createWithContent('clientes.csv', $csv))
            ->call('import')
            ->assertSet('status', 'No se encontraron clientes nuevos para importar.');

        $this->assertSame(1, Client::query()->count());

        $client->refresh();

        $this->assertSame('Ana', $client->nombre);
        $this->assertSame('Pérez', $client->apellidos);
        $this->assertSame('+34600123123', $client->telefono);
    }

    public function test_import_restores_soft_deleted_same_client_instead_of_creating_duplicate(): void
    {
        $admin = User::factory()->create();

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        $client->delete();

        $csv = <<<'CSV'
nombre;apellidos;telefono
Ana;Pérez;600123123
CSV;

        $this->actingAs($admin);

        Livewire::test(ClientCsvImporter::class)
            ->set('file', UploadedFile::fake()->createWithContent('clientes.csv', $csv))
            ->call('import')
            ->assertSet('status', 'Se restauraron 1 cliente(s) eliminado(s).');

        $this->assertSame(1, Client::withTrashed()->count());
        $this->assertSame(1, Client::query()->count());

        $client = Client::query()->firstOrFail();

        $this->assertSame('Ana', $client->nombre);
        $this->assertSame('Pérez', $client->apellidos);
        $this->assertFalse($client->trashed());
    }
}
