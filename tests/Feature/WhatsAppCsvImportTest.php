<?php

namespace Tests\Feature;

use App\Livewire\ExcelImporter;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;

class WhatsAppCsvImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_import_whatsapp_messages_from_csv(): void
    {
        Carbon::setTestNow('2026-06-22 10:00:00');

        $admin = User::factory()->create();
        $csv = <<<'CSV'
nombre,apellidos,telefono,fecha,hora
Ana,Pérez,600123123,2026-06-24,15:30
CSV;

        $this->actingAs($admin);

        Livewire::test(ExcelImporter::class)
            ->set('template_key', 'clinical_reminder')
            ->set('file', UploadedFile::fake()->createWithContent('citas.csv', $csv))
            ->call('import')
            ->assertSet('status', 'Archivo importado correctamente.');

        $this->assertDatabaseCount('clients', 1);
        $this->assertDatabaseCount('whatsapp_messages', 1);

        $client = Client::query()->firstOrFail();
        $message = WhatsAppMessage::query()->firstOrFail();

        $this->assertSame('Ana', $client->nombre);
        $this->assertSame('Pérez', $client->apellidos);
        $this->assertSame('+34600123123', $client->telefono);
        $this->assertSame($client->id, $message->client_id);
        $this->assertSame('csv', $message->source);
        $this->assertSame('csv', $message->metadata['imported_from']);
        $this->assertSame('clinical_reminder', $message->metadata['template_key']);
        $this->assertSame('2026-06-24 15:30:00', $message->scheduled_for->toDateTimeString());

        Carbon::setTestNow();
    }

    public function test_admin_can_import_whatsapp_messages_from_semicolon_csv(): void
    {
        Carbon::setTestNow('2026-06-22 10:00:00');

        $admin = User::factory()->create();
        $csv = <<<'CSV'
nombre;apellidos;telefono;fecha;hora
Ana;Pérez;600123123;2026-06-24;15:30
CSV;

        $this->actingAs($admin);

        Livewire::test(ExcelImporter::class)
            ->set('template_key', 'clinical_reminder')
            ->set('file', UploadedFile::fake()->createWithContent('citas.csv', $csv))
            ->call('import')
            ->assertSet('status', 'Archivo importado correctamente.');

        $this->assertDatabaseCount('clients', 1);
        $this->assertDatabaseCount('whatsapp_messages', 1);

        $message = WhatsAppMessage::query()->firstOrFail();

        $this->assertSame('2026-06-24 15:30:00', $message->scheduled_for->toDateTimeString());

        Carbon::setTestNow();
    }
}
