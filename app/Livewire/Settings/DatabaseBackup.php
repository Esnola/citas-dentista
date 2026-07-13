<?php

namespace App\Livewire\Settings;

use App\Models\Appointment;
use App\Models\AppointmentChange;
use App\Models\AppointmentReminderPreference;
use App\Models\AppSetting;
use App\Models\Client;
use App\Models\TwilioContentTemplate;
use App\Models\User;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppSenderNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use ZipArchive;

class DatabaseBackup extends Component
{
    use WithFileUploads;

    private const BOOL_FIELDS = [
        'is_admin', 'enviado', 'entregado', 'activo', 'cita_activa',
        'confirmada', 'pendiente_reprogramacion', 'enabled', 'selected',
        'seleccionada', 'dispatch_enabled',
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

    public function importDatabase(): void
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

        if ($version !== 1) {
            $this->importStatus = "Versión no soportada: {$version}";
            $this->importStatusNonce++;
            $this->confirmImport = false;

            return;
        }

        $tables = $decoded['tables'] ?? $decoded['settings'] ?? [];
        $this->applyData($tables);
    }

    private function importFromZip(): void
    {
        if (! class_exists(ZipArchive::class)) {
            $this->importStatus = 'ZipArchive no está disponible.';
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

        $tables = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            if ($filename === false || pathinfo($filename, PATHINFO_EXTENSION) !== 'csv') {
                continue;
            }

            $table = pathinfo($filename, PATHINFO_FILENAME);
            $tables[$table] = $this->parseCsvFromZip($zip, $filename);
        }

        $zip->close();
        $this->applyData($tables);
    }

    private function applyData(array $tables): void
    {
        DB::transaction(function () use ($tables): void {
            // Import in FK order
            if (isset($tables['users'])) {
                $this->importUsers($tables['users']);
            }

            if (isset($tables['clients'])) {
                $this->importClients($tables['clients']);
            }

            if (isset($tables['appointments'])) {
                $this->importAppointments($tables['appointments']);
            }

            if (isset($tables['appointment_changes'])) {
                $this->importAppointmentChanges($tables['appointment_changes']);
            }

            if (isset($tables['whatsapp_messages'])) {
                $this->importWhatsAppMessages($tables['whatsapp_messages']);
            }

            if (isset($tables['app_settings'])) {
                $this->importAppSettings($tables['app_settings']);
            }

            if (isset($tables['appointment_reminder_preferences'])) {
                $this->importReminderPreferences($tables['appointment_reminder_preferences']);
            }

            if (isset($tables['whatsapp_credentials'])) {
                $this->importCredentials($tables['whatsapp_credentials']);
            }

            if (isset($tables['whatsapp_sender_numbers'])) {
                $this->importSenderNumbers($tables['whatsapp_sender_numbers']);
            }

            if (isset($tables['twilio_content_templates'])) {
                $this->importTemplates($tables['twilio_content_templates']);
            }
        });

        $count = count(array_filter($tables));
        $this->importStatus = "{$count} tabla(s) importada(s) correctamente.";
        $this->importStatusNonce++;
        $this->confirmImport = false;
        $this->importFile = null;
    }

    private function importUsers(array $records): void
    {
        foreach ($records as $record) {
            $existing = User::find($record['id'] ?? null);

            $data = [
                'name' => $record['name'] ?? '',
                'email' => $record['email'] ?? '',
                'is_admin' => $record['is_admin'] ?? false,
            ];

            if (! empty($record['password']) && ! Hash::isHashed($record['password'])) {
                $data['password'] = Hash::make($record['password']);
            }

            if ($existing) {
                $existing->update($data);
            } else {
                unset($record['id']);
                User::create($data);
            }
        }
    }

    private function importClients(array $records): void
    {
        foreach ($records as $record) {
            Client::upsertFromImport($record);
        }
    }

    private function importAppointments(array $records): void
    {
        foreach ($records as $record) {
            $existing = null;

            if (! empty($record['id'])) {
                $existing = Appointment::find($record['id']);
            }

            $data = [
                'client_id' => $record['client_id'],
                'fecha' => $record['fecha'],
                'hora' => $record['hora'],
                'fecha_original' => $record['fecha_original'] ?? null,
                'hora_original' => $record['hora_original'] ?? null,
                'enviado' => $record['enviado'] ?? false,
                'entregado' => $record['entregado'] ?? false,
                'whatsapp_sent_at' => $record['whatsapp_sent_at'] ?? null,
                'whatsapp_delivered_at' => $record['whatsapp_delivered_at'] ?? null,
                'whatsapp_read_at' => $record['whatsapp_read_at'] ?? null,
                'activo' => $record['activo'] ?? true,
                'cita_activa' => $record['cita_activa'] ?? true,
                'confirmada' => $record['confirmada'] ?? false,
                'pendiente_reprogramacion' => $record['pendiente_reprogramacion'] ?? false,
            ];

            if ($existing) {
                $existing->update($data);
            } else {
                unset($record['id']);
                Appointment::create($data);
            }
        }
    }

    private function importAppointmentChanges(array $records): void
    {
        foreach ($records as $record) {
            $existing = null;

            if (! empty($record['id'])) {
                $existing = AppointmentChange::find($record['id']);
            }

            if ($existing) {
                $existing->update($record);
            } else {
                unset($record['id']);
                AppointmentChange::create($record);
            }
        }
    }

    private function importWhatsAppMessages(array $records): void
    {
        foreach ($records as $record) {
            $existing = null;

            if (! empty($record['id'])) {
                $existing = WhatsAppMessage::find($record['id']);
            }

            foreach (['provider_payload', 'metadata'] as $jsonField) {
                if (isset($record[$jsonField]) && is_string($record[$jsonField])) {
                    $record[$jsonField] = json_decode($record[$jsonField], true);
                }
            }

            if ($existing) {
                $existing->update($record);
            } else {
                unset($record['id']);
                WhatsAppMessage::create($record);
            }
        }
    }

    private function importAppSettings(array $records): void
    {
        foreach ($records as $record) {
            AppSetting::updateOrCreate([], [
                'retention_period' => $record['retention_period'] ?? 'disabled',
                'dispatch_enabled' => $record['dispatch_enabled'] ?? true,
                'dispatch_hours' => is_string($record['dispatch_hours'] ?? null)
                    ? json_decode($record['dispatch_hours'], true)
                    : ($record['dispatch_hours'] ?? ['09:00', '12:00', '15:00']),
                'twilio_template_appointment_reminder_id' => $record['twilio_template_appointment_reminder_id'] ?? null,
                'twilio_template_appointment_created_id' => $record['twilio_template_appointment_created_id'] ?? null,
            ]);
        }
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
                    'content_variables' => is_string($record['content_variables'] ?? null)
                        ? json_decode($record['content_variables'], true)
                        : ($record['content_variables'] ?? []),
                ],
            );
        }
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
            } elseif (in_array($key, self::BOOL_FIELDS, true)) {
                $result[$key] = $value === '1';
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
        return view('settings.database-backup');
    }
}
