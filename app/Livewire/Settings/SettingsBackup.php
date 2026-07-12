<?php

namespace App\Livewire\Settings;

use App\Models\AppointmentReminderPreference;
use App\Models\AppSetting;
use App\Models\TwilioContentTemplate;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppSenderNumber;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use ZipArchive;

class SettingsBackup extends Component
{
    use WithFileUploads;

    private const ENCRYPTED_FIELDS = [
        'account_sid',
        'auth_token',
        'api_key_sid',
        'api_key_secret',
        'cloud_api_access_token',
    ];

    public $importFile;

    public string $importStatus = '';

    public int $importStatusNonce = 0;

    public bool $confirmImport = false;

    public function mount(): void
    {
        //
    }

    public function updatedImportFile(): void
    {
        $this->confirmImport = false;
        $this->importStatus = '';
    }

    public function importSettings(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        if (! $this->importFile) {
            $this->importStatus = 'Selecciona un archivo.';
            $this->importStatusNonce++;

            return;
        }

        if (! $this->confirmImport) {
            $this->confirmImport = true;
            $this->importStatus = 'Pulsa de nuevo en Importar para confirmar.';
            $this->importStatusNonce++;

            return;
        }

        $originalName = $this->importFile->getClientOriginalName();
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if ($extension === 'json') {
            $this->importFromJson();
        } elseif ($extension === 'zip') {
            $this->importFromZip();
        } else {
            $this->importStatus = 'Formato no soportado. Usa .json o .zip.';
            $this->importStatusNonce++;
            $this->confirmImport = false;

            return;
        }
    }

    private function importFromJson(): void
    {
        $json = file_get_contents($this->importFile->getRealPath());

        if ($json === false) {
            $this->importStatus = 'No se pudo leer el archivo.';
            $this->importStatusNonce++;
            $this->confirmImport = false;

            return;
        }

        $decoded = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->importStatus = 'JSON inválido: '.json_last_error_msg();
            $this->importStatusNonce++;
            $this->confirmImport = false;

            return;
        }

        $version = $decoded['version'] ?? null;

        if (! in_array($version, [1, 2], true)) {
            $this->importStatus = "Versión no soportada: {$version}";
            $this->importStatusNonce++;
            $this->confirmImport = false;

            return;
        }

        $settings = $decoded['settings'] ?? [];

        DB::transaction(function () use ($settings, $version): void {
            if ($version === 1) {
                $this->importV1($settings);
            } else {
                $this->importV2($settings);
            }
        });

        $this->importStatus = 'Ajustes importados correctamente.';
        $this->importStatusNonce++;
        $this->confirmImport = false;
        $this->importFile = null;
    }

    private function importV1(array $settings): void
    {
        $retentionPeriod = $settings['sistema_opciones']['retention_period'] ?? 'disabled';
        $dispatchEnabled = $settings['whatsapp_dispatch_settings']['enabled'] ?? true;
        $dispatchHours = $settings['whatsapp_dispatch_settings']['hours'] ?? ['09:00', '12:00', '15:00'];

        AppSetting::updateOrCreate([], [
            'retention_period' => $retentionPeriod,
            'dispatch_enabled' => $dispatchEnabled,
            'dispatch_hours' => $dispatchHours,
        ]);

        $this->importReminderPreferences($settings['appointment_reminder_preferences'] ?? []);
        $this->importCredentials($settings['whatsapp_credentials'] ?? []);
        $this->importSenderNumbers($settings['whatsapp_sender_numbers'] ?? []);
        $this->importTemplates($settings['twilio_content_templates'] ?? []);
    }

    private function importV2(array $settings): void
    {
        if (! empty($settings['app_settings'])) {
            $data = $settings['app_settings'];
            AppSetting::updateOrCreate([], [
                'retention_period' => $data['retention_period'] ?? 'disabled',
                'dispatch_enabled' => $data['dispatch_enabled'] ?? true,
                'dispatch_hours' => $data['dispatch_hours'] ?? ['09:00', '12:00', '15:00'],
            ]);
        }

        $this->importReminderPreferences($settings['appointment_reminder_preferences'] ?? []);
        $this->importCredentials($settings['whatsapp_credentials'] ?? []);
        $this->importSenderNumbers($settings['whatsapp_sender_numbers'] ?? []);
        $this->importTemplates($settings['twilio_content_templates'] ?? []);
    }

    private function importFromZip(): void
    {
        if (! class_exists(ZipArchive::class)) {
            $this->importStatus = 'ZipArchive no está disponible en el servidor.';
            $this->importStatusNonce++;
            $this->confirmImport = false;

            return;
        }

        $zipPath = $this->importFile->getRealPath();
        $zip = new ZipArchive;

        if ($zip->open($zipPath) !== true) {
            $this->importStatus = 'No se pudo abrir el ZIP.';
            $this->importStatusNonce++;
            $this->confirmImport = false;

            return;
        }

        $settings = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            if ($filename === false || pathinfo($filename, PATHINFO_EXTENSION) !== 'csv') {
                continue;
            }

            $table = pathinfo($filename, PATHINFO_FILENAME);
            $settings[$table] = $this->parseCsvFromZip($zip, $filename);
        }

        $zip->close();

        DB::transaction(function () use ($settings): void {
            if (isset($settings['app_settings'])) {
                $row = $settings['app_settings'][0] ?? [];
                AppSetting::updateOrCreate([], [
                    'retention_period' => $row['retention_period'] ?? 'disabled',
                    'dispatch_enabled' => $row['dispatch_enabled'] ?? true,
                    'dispatch_hours' => $row['dispatch_hours'] ?? ['09:00', '12:00', '15:00'],
                ]);
            }

            if (isset($settings['appointment_reminder_preferences'])) {
                $this->importReminderPreferences($settings['appointment_reminder_preferences']);
            }

            if (isset($settings['whatsapp_credentials'])) {
                $this->importCredentials($settings['whatsapp_credentials']);
            }

            if (isset($settings['whatsapp_sender_numbers'])) {
                $this->importSenderNumbers($settings['whatsapp_sender_numbers']);
            }

            if (isset($settings['twilio_content_templates'])) {
                $this->importTemplates($settings['twilio_content_templates']);
            }
        });

        $this->importStatus = 'Ajustes importados correctamente.';
        $this->importStatusNonce++;
        $this->confirmImport = false;
        $this->importFile = null;
    }

    private function parseCsvFromZip(ZipArchive $zip, string $filename): array
    {
        $content = $zip->getFromName($filename);

        if ($content === false) {
            return [];
        }

        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $content);
        rewind($handle);

        $headings = fgetcsv($handle);
        if ($headings === false) {
            fclose($handle);

            return [];
        }

        $rows = [];

        while (($row = fgetcsv($handle)) !== false) {
            $row = array_combine($headings, $row);
            $rows[] = $this->unflattenFromCsv($row);
        }

        fclose($handle);

        return $rows;
    }

    private function unflattenFromCsv(array $row): array
    {
        $result = [];

        foreach ($row as $key => $value) {
            if ($value === '' || $value === null) {
                $result[$key] = null;
            } elseif ($value === '1' && in_array($key, ['enabled', 'seleccionada', 'selected', 'dispatch_enabled'], true)) {
                $result[$key] = true;
            } elseif ($value === '0' && in_array($key, ['enabled', 'seleccionada', 'selected', 'dispatch_enabled'], true)) {
                $result[$key] = false;
            } elseif ($value !== '' && $this->isJsonString($value)) {
                $result[$key] = json_decode($value, true);
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    private function isJsonString(string $value): bool
    {
        if ($value[0] !== '{' && $value[0] !== '[') {
            return false;
        }

        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE && is_array($decoded);
    }

    public function render()
    {
        return view('settings.settings-backup');
    }

    private function importReminderPreferences(array $records): void
    {
        foreach ($records as $record) {
            AppointmentReminderPreference::updateOrCreate(
                ['channel' => $record['channel'], 'lead_days' => $record['lead_days']],
                ['enabled' => $record['enabled'] ?? false],
            );
        }
    }

    private function importCredentials(array $records): void
    {
        foreach ($records as $record) {
            foreach (self::ENCRYPTED_FIELDS as $field) {
                if (! empty($record[$field])) {
                    $record[$field] = $this->encryptValue($record[$field]);
                }
            }

            $existing = null;

            if (! empty($record['id'])) {
                $existing = WhatsAppCredential::find($record['id']);
            }

            if ($existing) {
                $existing->update($record);
            } else {
                unset($record['id']);
                WhatsAppCredential::create($record);
            }
        }
    }

    private function importSenderNumbers(array $records): void
    {
        foreach ($records as $record) {
            $existing = null;

            if (! empty($record['id'])) {
                $existing = WhatsAppSenderNumber::find($record['id']);
            }

            if ($existing) {
                $existing->update($record);
            } else {
                unset($record['id']);
                WhatsAppSenderNumber::create($record);
            }
        }
    }

    private function importTemplates(array $records): void
    {
        foreach ($records as $record) {
            TwilioContentTemplate::updateOrCreate(
                ['content_sid' => $record['content_sid']],
                [
                    'nombre' => $record['nombre'] ?? '',
                    'seleccionada' => $record['seleccionada'] ?? false,
                    'content_variables' => $record['content_variables'] ?? [],
                ],
            );
        }
    }

    private function encryptValue(string $value): string
    {
        try {
            Crypt::decrypt($value);

            return $value;
        } catch (\Throwable) {
            return Crypt::encrypt($value);
        }
    }
}
