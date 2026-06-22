<?php

namespace Tests\Feature;

use App\Imports\WhatsAppMessagesImport;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class WhatsAppExcelPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_preview_mode_returns_rows_without_persisting_messages(): void
    {
        $admin = User::factory()->create();

        $import = new WhatsAppMessagesImport($admin, 'clinical_reminder', false);
        $import->collection(new Collection([
            [
                'NOMBRE' => 'Ana',
                'APELLIDOS' => 'Pérez',
                'TELÉFONO' => '600123123',
                'FECHA' => '2026-06-22',
                'HORA' => '15:30',
            ],
        ]));

        $this->assertSame(0, WhatsAppMessage::query()->count());

        $rows = $import->previewRows();

        $this->assertCount(1, $rows);
        $this->assertSame('Ana', $rows[0]['nombre']);
        $this->assertSame('Pérez', $rows[0]['apellidos']);
        $this->assertSame('600123123', $rows[0]['telefono']);
        $this->assertSame('Hola Ana te recordamos que el día 22/06/2026 tienes una cita a las 15:30 ; saludos Clínica Dental Eugénia', $rows[0]['message']);
    }
}
