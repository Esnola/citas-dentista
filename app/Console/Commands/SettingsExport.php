<?php

namespace App\Console\Commands;

use App\Models\AppointmentReminderPreference;
use App\Models\SistemaOpcion;
use App\Models\TwilioContentTemplate;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppDispatchSettings;
use App\Models\WhatsAppSenderNumber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;

class SettingsExport extends Command
{
    private const ENCRYPTED_FIELDS = [
        'account_sid',
        'auth_token',
        'api_key_sid',
        'api_key_secret',
        'cloud_api_access_token',
    ];

    protected $signature = 'settings:export {path?}';

    protected $description = 'Export all settings to a JSON backup file.';

    public function handle(): int
    {
        $path = $this->argument('path') ?? 'storage/app/settings-backup.json';

        $data = [
            'version' => 1,
            'exported_at' => now()->toIso8601String(),
            'settings' => [
                'sistema_opciones' => $this->exportSistemaOpcion(),
                'whatsapp_dispatch_settings' => $this->exportDispatchSettings(),
                'appointment_reminder_preferences' => $this->exportReminderPreferences(),
                'whatsapp_credentials' => $this->exportCredentials(),
                'whatsapp_sender_numbers' => $this->exportSenderNumbers(),
                'twilio_content_templates' => $this->exportTemplates(),
            ],
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($json === false) {
            $this->error('Failed to encode settings as JSON.');

            return self::FAILURE;
        }

        $fullPath = str_starts_with($path, '/') ? $path : base_path($path);

        if (! is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        $written = file_put_contents($fullPath, $json);

        if ($written === false) {
            $this->error("Failed to write to {$fullPath}.");

            return self::FAILURE;
        }

        $this->info("Settings exported to {$fullPath}");

        return self::SUCCESS;
    }

    private function exportSistemaOpcion(): ?array
    {
        $model = SistemaOpcion::query()->first();

        return $model?->only(['id', 'retention_period']);
    }

    private function exportDispatchSettings(): ?array
    {
        $model = WhatsAppDispatchSettings::query()->first();

        return $model?->only(['id', 'enabled', 'hours']);
    }

    private function exportReminderPreferences(): array
    {
        return AppointmentReminderPreference::query()
            ->select(['id', 'channel', 'lead_days', 'enabled'])
            ->get()
            ->toArray();
    }

    private function exportCredentials(): array
    {
        $credentials = WhatsAppCredential::query()->get();

        return $credentials->map(function (WhatsAppCredential $credential): array {
            $data = $credential->only([
                'id',
                'driver',
                'default_country_code',
                'message_mode',
                'account_sid',
                'auth_token',
                'content_sid',
                'test_recipient',
                'timeout',
                'connect_timeout',
                'cloud_api_base_url',
                'cloud_api_version',
                'cloud_api_phone_number_id',
                'cloud_api_access_token',
                'cloud_api_timeout',
                'default_template',
                'default_message',
                'mode',
                'api_key_sid',
                'api_key_secret',
                'status_callback_url',
                'selected',
            ]);

            foreach (self::ENCRYPTED_FIELDS as $field) {
                if (! empty($data[$field])) {
                    $data[$field] = $this->decryptValue($data[$field]);
                }
            }

            return $data;
        })->toArray();
    }

    private function exportSenderNumbers(): array
    {
        return WhatsAppSenderNumber::query()
            ->select(['id', 'whatsapp_credential_id', 'name', 'prefix', 'number', 'selected'])
            ->get()
            ->toArray();
    }

    private function exportTemplates(): array
    {
        return TwilioContentTemplate::query()
            ->select(['id', 'nombre', 'content_sid', 'seleccionada', 'content_variables'])
            ->get()
            ->toArray();
    }

    private function decryptValue(string $value): string
    {
        try {
            return Crypt::decrypt($value);
        } catch (\Throwable) {
            return $value;
        }
    }
}
