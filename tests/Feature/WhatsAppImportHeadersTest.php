<?php

namespace Tests\Feature;

use App\Imports\WhatsAppMessagesImport;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class WhatsAppImportHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_accepts_uppercase_and_spaced_headers(): void
    {
        Carbon::setTestNow('2026-06-22 10:00:00');

        $admin = User::factory()->create();

        $import = new WhatsAppMessagesImport($admin, 'short_reminder');
        $import->collection(new Collection([
            [
                'NOMBRE COMPLETO' => 'Ana',
                'APELLIDOS' => 'Pérez',
                'TELÉFONO' => '600123123',
                'FECHA CITA' => '2026-06-22',
                'HORA CITA' => '15:30',
            ],
        ]));

        $message = WhatsAppMessage::query()->firstOrFail();
        $client = Client::query()->firstOrFail();

        $this->assertSame('Ana', $message->nombre);
        $this->assertSame('Pérez', $message->apellidos);
        $this->assertSame('600123123', $message->telefono);
        $this->assertSame($client->id, $message->client_id);
        $this->assertSame(
            'Hola Ana, recuerde su cita el 22/06/2026 a las 15:30. Tel: 600123123',
            $message->message
        );
        $this->assertSame('short_reminder', $message->metadata['template_key']);
        $this->assertSame('Ana', $client->nombre);
        $this->assertSame('Pérez', $client->apellidos);
        $this->assertSame('+34600123123', $client->telefono);
        $this->assertSame('2026-06-22', $client->created_at->toDateString());
        $this->assertSame('10:00', $client->created_at->format('H:i'));

        Carbon::setTestNow();
    }

    public function test_import_updates_existing_client_instead_of_creating_duplicates(): void
    {
        Carbon::setTestNow('2026-06-20 09:00:00');

        $admin = User::factory()->create();

        Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        Carbon::setTestNow('2026-06-24 10:45:00');

        $import = new WhatsAppMessagesImport($admin, 'short_reminder');
        $import->collection(new Collection([
            [
                'NOMBRE' => 'Ana María',
                'APELLIDOS' => 'Pérez López',
                'TELÉFONO' => '600123123',
                'FECHA' => '2026-06-24',
                'HORA' => '10:45',
            ],
        ]));

        $this->assertSame(1, Client::query()->count());

        $client = Client::query()->firstOrFail();

        $this->assertSame('Ana María', $client->nombre);
        $this->assertSame('Pérez López', $client->apellidos);
        $this->assertSame('+34600123123', $client->telefono);
        $this->assertSame('2026-06-20', $client->created_at->toDateString());

        Carbon::setTestNow();
    }
}
