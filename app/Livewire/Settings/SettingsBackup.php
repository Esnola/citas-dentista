<?php

namespace App\Livewire\Settings;

use App\Models\AppointmentReminderPreference;
use App\Models\SistemaOpcion;
use App\Models\TwilioContentTemplate;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppDispatchSettings;
use App\Models\WhatsAppSenderNumber;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

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

    private const ALL_SECTIONS = [
        'sistema_opciones' => 'Opciones del sistema',
        'whatsapp_dispatch_settings' => 'Configuración de envíos',
        'appointment_reminder_preferences' => 'Preferencias de recordatorios',
        'whatsapp_credentials' => 'Credenciales WhatsApp',
        'whatsapp_sender_numbers' => 'Números de envío',
        'twilio_content_templates' => 'Plantillas de Twilio',
    ];

    /** @var array<string, bool> */
    public array $selectedImport = [];

    public $importFile;

    public string $importStatus = '';

    public int $importStatusNonce = 0;

    public bool $confirmImport = false;

    public function mount(): void
    {
        $this->selectedImport = array_fill_keys(array_keys(self::ALL_SECTIONS), true);
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
            $this->importStatus = 'Selecciona un archivo JSON.';
            $this->importStatusNonce++;

            return;
        }

        if (! $this->confirmImport) {
            $this->confirmImport = true;
            $this->importStatus = 'Pulsa de nuevo en Importar para confirmar.';
            $this->importStatusNonce++;

            return;
        }

        $json = file_get_contents($this->importFile->getRealPath());

        if ($json === false) {
            $this->importStatus = 'No se pudo leer el archivo.';
            $this->importStatusNonce++;

            return;
        }

        $decoded = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->importStatus = 'JSON inválido: '.json_last_error_msg();
            $this->importStatusNonce++;

            return;
        }

        $version = $decoded['version'] ?? null;

        if ($version !== 1) {
            $this->importStatus = "Versión no soportada: {$version}";
            $this->importStatusNonce++;

            return;
        }

        $settings = $decoded['settings'] ?? [];
        $sections = array_filter($this->selectedImport);

        DB::transaction(function () use ($settings, $sections): void {
            if (isset($sections['sistema_opciones']) && isset($settings['sistema_opciones'])) {
                $this->importSistemaOpcion($settings['sistema_opciones']);
            }

            if (isset($sections['whatsapp_dispatch_settings']) && isset($settings['whatsapp_dispatch_settings'])) {
                $this->importDispatchSettings($settings['whatsapp_dispatch_settings']);
            }

            if (isset($sections['appointment_reminder_preferences']) && isset($settings['appointment_reminder_preferences'])) {
                $this->importReminderPreferences($settings['appointment_reminder_preferences']);
            }

            if (isset($sections['whatsapp_credentials']) && isset($settings['whatsapp_credentials'])) {
                $this->importCredentials($settings['whatsapp_credentials']);
            }

            if (isset($sections['whatsapp_sender_numbers']) && isset($settings['whatsapp_sender_numbers'])) {
                $this->importSenderNumbers($settings['whatsapp_sender_numbers']);
            }

            if (isset($sections['twilio_content_templates']) && isset($settings['twilio_content_templates'])) {
                $this->importTemplates($settings['twilio_content_templates']);
            }
        });

        $count = count($sections);
        $this->importStatus = "{$count} sección(es) importada(s) correctamente.";
        $this->importStatusNonce++;
        $this->confirmImport = false;
        $this->importFile = null;
    }

    public function render()
    {
        return view('settings.settings-backup', [
            'sections' => self::ALL_SECTIONS,
        ]);
    }

    private function importSistemaOpcion(array $data): void
    {
        SistemaOpcion::updateOrCreate([], [
            'retention_period' => $data['retention_period'] ?? 'disabled',
        ]);
    }

    private function importDispatchSettings(array $data): void
    {
        WhatsAppDispatchSettings::updateOrCreate([], [
            'enabled' => $data['enabled'] ?? true,
            'hours' => $data['hours'] ?? ['09:00', '12:00', '15:00'],
        ]);
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
