<?php

namespace Tests\Feature;

use App\Livewire\Settings\DatabaseBackup;
use App\Livewire\Settings\SettingsBackup;
use App\Livewire\Settings\TableBackup;
use App\Models\Appointment;
use App\Models\AppSetting;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppCredential;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;

class BackupRoundTripTest extends TestCase
{
    use RefreshDatabase;

    private function exportAndRead(string $route, string $filename): string
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get(route($route))
            ->assertOk();

        $pathPattern = storage_path("app/{$filename}");
        $files = glob($pathPattern);
        $this->assertNotEmpty($files, "No export file found matching: {$pathPattern}");

        $content = file_get_contents($files[0]);
        @unlink($files[0]);

        return $content;
    }

    private function makeImportFile(string $content, string $name): UploadedFile
    {
        $tmpPath = tempnam(sys_get_temp_dir(), 'backup-test-');
        file_put_contents($tmpPath, $content);

        return UploadedFile::fake()->createWithContent($name, file_get_contents($tmpPath));
    }

    // ── TableBackup ─────────────────────────────────────────────

    public function test_clients_json_round_trip(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Client::query()->create([
            'nombre' => 'María',
            'apellidos' => 'García',
            'telefono' => '+34 611 222 333',
            'fecha' => today()->toDateString(),
            'hora' => '09:00:00',
        ]);

        $json = $this->exportAndRead('admin.export.clients-json', 'clientes.json');
        $records = json_decode($json, true);

        $this->assertCount(1, $records);
        $this->assertEquals('María', $records[0]['nombre']);

        Client::query()->forceDelete();

        $file = $this->makeImportFile($json, 'clientes.json');

        Livewire::actingAs($admin)
            ->test(TableBackup::class)
            ->set('selectedTable', 'clients')
            ->set('importFile', $file)
            ->call('importTable')
            ->call('importTable')
            ->assertSet('importStatus', '1 registro(s) importado(s) en Clientes.');

        $this->assertDatabaseHas('clients', ['nombre' => 'María', 'apellidos' => 'García']);
    }

    public function test_appointments_import_from_json(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $client = Client::query()->create([
            'nombre' => 'Test',
            'apellidos' => 'Client',
            'telefono' => '+34 600 111 222',
            'fecha' => today()->toDateString(),
            'hora' => '10:00:00',
        ]);

        $data = [
            [
                'id' => 1,
                'client_id' => $client->id,
                'fecha' => '2026-07-15',
                'hora' => '11:00:00',
                'enviado' => true,
                'activo' => true,
            ],
        ];

        $file = $this->makeImportFile(json_encode($data), 'citas.json');

        Livewire::actingAs($admin)
            ->test(TableBackup::class)
            ->set('selectedTable', 'appointments')
            ->set('importFile', $file)
            ->call('importTable')
            ->call('importTable')
            ->assertSet('importStatus', '1 registro(s) importado(s) en Citas.');

        $this->assertDatabaseHas('appointments', ['client_id' => $client->id]);
    }

    public function test_appointments_json_export_contains_all_fields(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'López',
            'telefono' => '+34 600 000 000',
            'fecha' => today()->toDateString(),
            'hora' => '10:00:00',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => today()->addDay()->toDateString(),
            'hora' => '11:00:00',
            'enviado' => true,
            'activo' => true,
        ]);

        $json = $this->exportAndRead('admin.export.appointments-json', 'citas.json');
        $records = json_decode($json, true);

        $this->assertCount(1, $records);
        $this->assertArrayHasKey('client_id', $records[0]);
        $this->assertEquals($client->id, $records[0]['client_id']);
        $this->assertTrue($records[0]['enviado']);
    }

    public function test_import_duplicate_clients_does_not_create_duplicates(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Client::query()->create([
            'nombre' => 'Carlos',
            'apellidos' => 'Ruiz',
            'telefono' => '+34 699 111 222',
            'fecha' => today()->toDateString(),
            'hora' => '08:00:00',
        ]);

        $json = $this->exportAndRead('admin.export.clients-json', 'clientes.json');
        $file = $this->makeImportFile($json, 'clientes.json');

        Livewire::actingAs($admin)
            ->test(TableBackup::class)
            ->set('selectedTable', 'clients')
            ->set('importFile', $file)
            ->call('importTable')
            ->call('importTable');

        $this->assertEquals(1, Client::query()->count());
    }

    public function test_non_admin_cannot_import(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $file = $this->makeImportFile('{}', 'test.json');

        Livewire::actingAs($user)
            ->test(TableBackup::class)
            ->set('selectedTable', 'clients')
            ->set('importFile', $file)
            ->call('importTable')
            ->assertStatus(403);
    }

    // ── SettingsBackup ──────────────────────────────────────────

    public function test_settings_json_v2_import(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $data = [
            'version' => 2,
            'settings' => [
                'app_settings' => [
                    'retention_period' => '1_month',
                    'dispatch_enabled' => false,
                    'dispatch_hours' => ['08:00', '10:00'],
                ],
                'appointment_reminder_preferences' => [
                    ['channel' => 'whatsapp', 'lead_days' => 1, 'enabled' => true],
                ],
                'whatsapp_credentials' => [],
                'whatsapp_sender_numbers' => [],
                'twilio_content_templates' => [],
            ],
        ];

        $file = $this->makeImportFile(json_encode($data), 'settings.json');

        Livewire::actingAs($admin)
            ->test(SettingsBackup::class)
            ->set('importFile', $file)
            ->call('importSettings')
            ->call('importSettings')
            ->assertSet('importStatus', 'Ajustes importados correctamente.');

        $this->assertDatabaseHas('app_settings', [
            'retention_period' => '1_month',
            'dispatch_enabled' => false,
        ]);

        $this->assertDatabaseHas('appointment_reminder_preferences', [
            'channel' => 'whatsapp',
            'lead_days' => 1,
            'enabled' => true,
        ]);
    }

    public function test_settings_import_v1_backward_compat(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $v1Json = json_encode([
            'version' => 1,
            'settings' => [
                'sistema_opciones' => [
                    'retention_period' => '2_weeks',
                ],
                'whatsapp_dispatch_settings' => [
                    'enabled' => true,
                    'hours' => ['09:00', '12:00'],
                ],
                'appointment_reminder_preferences' => [],
                'whatsapp_credentials' => [],
                'whatsapp_sender_numbers' => [],
                'twilio_content_templates' => [],
            ],
        ]);

        $file = $this->makeImportFile($v1Json, 'v1.json');

        Livewire::actingAs($admin)
            ->test(SettingsBackup::class)
            ->set('importFile', $file)
            ->call('importSettings')
            ->call('importSettings')
            ->assertSet('importStatus', 'Ajustes importados correctamente.');

        $this->assertDatabaseHas('app_settings', [
            'retention_period' => '2_weeks',
            'dispatch_enabled' => true,
        ]);
    }

    public function test_settings_credentials_are_encrypted_in_db(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $data = [
            'version' => 2,
            'settings' => [
                'app_settings' => null,
                'appointment_reminder_preferences' => [],
                'whatsapp_credentials' => [
                    [
                        'driver' => 'twilio',
                        'account_sid' => 'AC_test_sid_123',
                        'auth_token' => 'auth_token_secret_456',
                    ],
                ],
                'whatsapp_sender_numbers' => [],
                'twilio_content_templates' => [],
            ],
        ];

        $file = $this->makeImportFile(json_encode($data), 'settings.json');

        Livewire::actingAs($admin)
            ->test(SettingsBackup::class)
            ->set('importFile', $file)
            ->call('importSettings')
            ->call('importSettings');

        $imported = WhatsAppCredential::query()->first();
        $this->assertNotNull($imported);
        // Eloquent encrypted cast auto-decrypts on read
        $this->assertEquals('AC_test_sid_123', $imported->account_sid);
        $this->assertEquals('auth_token_secret_456', $imported->auth_token);
    }

    // ── DatabaseBackup ──────────────────────────────────────────

    public function test_full_database_json_structure(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $client = Client::query()->create([
            'nombre' => 'Laura',
            'apellidos' => 'Martín',
            'telefono' => '+34 677 333 444',
            'fecha' => today()->toDateString(),
            'hora' => '11:00:00',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => today()->addDay()->toDateString(),
            'hora' => '12:00:00',
            'activo' => true,
        ]);

        AppSetting::query()->create([
            'retention_period' => '3_months',
            'dispatch_enabled' => true,
            'dispatch_hours' => ['10:00', '14:00'],
        ]);

        $json = $this->exportAndRead('admin.export.all-json', 'database-backup-*.json');
        $decoded = json_decode($json, true);

        $this->assertEquals(1, $decoded['version']);
        $this->assertArrayHasKey('users', $decoded['tables']);
        $this->assertArrayHasKey('clients', $decoded['tables']);
        $this->assertArrayHasKey('appointments', $decoded['tables']);
        $this->assertArrayHasKey('app_settings', $decoded['tables']);
        $this->assertArrayHasKey('appointment_reminder_preferences', $decoded['tables']);
        $this->assertArrayHasKey('whatsapp_credentials', $decoded['tables']);
        $this->assertArrayHasKey('whatsapp_sender_numbers', $decoded['tables']);
        $this->assertArrayHasKey('twilio_content_templates', $decoded['tables']);
    }

    public function test_database_import_from_json(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $data = [
            'version' => 1,
            'tables' => [
                'users' => [],
                'clients' => [
                    [
                        'nombre' => 'Imported',
                        'apellidos' => 'Client',
                        'telefono' => '+34 600 999 000',
                    ],
                ],
                'appointments' => [],
                'appointment_changes' => [],
                'whatsapp_messages' => [],
                'app_settings' => [
                    ['retention_period' => '6_months', 'dispatch_enabled' => true, 'dispatch_hours' => ['09:00']],
                ],
                'appointment_reminder_preferences' => [
                    ['channel' => 'whatsapp', 'lead_days' => 1, 'enabled' => true],
                ],
                'whatsapp_credentials' => [],
                'whatsapp_sender_numbers' => [],
                'twilio_content_templates' => [
                    ['nombre' => 'Test', 'content_sid' => 'HXimported123456789012345678', 'content_variables' => null],
                ],
            ],
        ];

        $file = $this->makeImportFile(json_encode($data), 'db.json');

        Livewire::actingAs($admin)
            ->test(DatabaseBackup::class)
            ->set('importFile', $file)
            ->call('importDatabase')
            ->call('importDatabase');

        $this->assertDatabaseHas('clients', ['nombre' => 'Imported']);
        $this->assertDatabaseHas('app_settings', ['retention_period' => '6_months']);
        $this->assertDatabaseHas('appointment_reminder_preferences', ['channel' => 'whatsapp', 'lead_days' => 1]);
        $this->assertDatabaseHas('twilio_content_templates', ['content_sid' => 'HXimported123456789012345678']);
    }

    public function test_database_import_credentials_are_encrypted(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $data = [
            'version' => 1,
            'tables' => [
                'users' => [],
                'clients' => [],
                'appointments' => [],
                'appointment_changes' => [],
                'whatsapp_messages' => [],
                'app_settings' => [],
                'appointment_reminder_preferences' => [],
                'whatsapp_credentials' => [
                    [
                        'driver' => 'twilio',
                        'account_sid' => 'AC_plain_text_sid',
                        'auth_token' => 'plain_text_token',
                    ],
                ],
                'whatsapp_sender_numbers' => [],
                'twilio_content_templates' => [],
            ],
        ];

        $file = $this->makeImportFile(json_encode($data), 'db.json');

        Livewire::actingAs($admin)
            ->test(DatabaseBackup::class)
            ->set('importFile', $file)
            ->call('importDatabase')
            ->call('importDatabase');

        $credential = WhatsAppCredential::query()->first();
        $this->assertNotNull($credential);
        // Eloquent encrypted cast auto-decrypts on read
        $this->assertEquals('AC_plain_text_sid', $credential->account_sid);
        $this->assertEquals('plain_text_token', $credential->auth_token);
    }

    public function test_non_admin_cannot_import_database(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $file = $this->makeImportFile('{}', 'test.json');

        Livewire::actingAs($user)
            ->test(DatabaseBackup::class)
            ->set('importFile', $file)
            ->call('importDatabase')
            ->assertStatus(403);
    }

    public function test_import_rejects_unsupported_format(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $file = $this->makeImportFile('hello', 'data.txt');

        Livewire::actingAs($admin)
            ->test(DatabaseBackup::class)
            ->set('importFile', $file)
            ->call('importDatabase')
            ->call('importDatabase')
            ->assertSet('importStatus', 'Formato no soportado. Usa .json o .zip.');
    }
}
