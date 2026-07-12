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
use Illuminate\Support\Facades\DB;

class SettingsImport extends Command
{
    private const ENCRYPTED_FIELDS = [
        'account_sid',
        'auth_token',
        'api_key_sid',
        'api_key_secret',
        'cloud_api_access_token',
    ];

    private const SUPPORTED_VERSION = 1;

    protected $signature = 'settings:import {path?} {--force : Apply without confirmation}';

    protected $description = 'Import settings from a JSON backup file.';

    public function handle(): int
    {
        $path = $this->argument('path') ?? 'storage/app/settings-backup.json';
        $fullPath = str_starts_with($path, '/') ? $path : base_path($path);

        if (! file_exists($fullPath)) {
            $this->error("File not found: {$fullPath}");

            return self::FAILURE;
        }

        $json = file_get_contents($fullPath);

        if ($json === false) {
            $this->error("Failed to read: {$fullPath}");

            return self::FAILURE;
        }

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON: '.json_last_error_msg());

            return self::FAILURE;
        }

        $version = $data['version'] ?? null;

        if ($version !== self::SUPPORTED_VERSION) {
            $this->error("Unsupported backup version: {$version} (expected ".self::SUPPORTED_VERSION.'.');

            return self::FAILURE;
        }

        $settings = $data['settings'] ?? [];

        if (! $this->option('force')) {
            $this->warn('The following settings will be imported:');
            $this->previewChanges($settings);

            if (! $this->confirm('Apply these changes?')) {
                $this->info('Import cancelled.');

                return self::SUCCESS;
            }
        }

        DB::transaction(function () use ($settings): void {
            $this->importSistemaOpcion($settings['sistema_opciones'] ?? null);
            $this->importDispatchSettings($settings['whatsapp_dispatch_settings'] ?? null);
            $this->importReminderPreferences($settings['appointment_reminder_preferences'] ?? []);
            $this->importCredentials($settings['whatsapp_credentials'] ?? []);
            $this->importSenderNumbers($settings['whatsapp_sender_numbers'] ?? []);
            $this->importTemplates($settings['twilio_content_templates'] ?? []);
        });

        $this->info('Settings imported successfully.');

        return self::SUCCESS;
    }

    private function previewChanges(array $settings): void
    {
        if (! empty($settings['sistema_opciones'])) {
            $this->line('  - sistema_opciones: retention_period = '.$settings['sistema_opciones']['retention_period']);
        }

        if (! empty($settings['whatsapp_dispatch_settings'])) {
            $dispatch = $settings['whatsapp_dispatch_settings'];
            $this->line('  - whatsapp_dispatch_settings: enabled = '.($dispatch['enabled'] ? 'true' : 'false').', hours = '.json_encode($dispatch['hours']));
        }

        if (! empty($settings['appointment_reminder_preferences'])) {
            $count = count($settings['appointment_reminder_preferences']);
            $this->line("  - appointment_reminder_preferences: {$count} record(s)");
        }

        if (! empty($settings['whatsapp_credentials'])) {
            $count = count($settings['whatsapp_credentials']);
            $this->line("  - whatsapp_credentials: {$count} record(s)");
        }

        if (! empty($settings['whatsapp_sender_numbers'])) {
            $count = count($settings['whatsapp_sender_numbers']);
            $this->line("  - whatsapp_sender_numbers: {$count} record(s)");
        }

        if (! empty($settings['twilio_content_templates'])) {
            $count = count($settings['twilio_content_templates']);
            $this->line("  - twilio_content_templates: {$count} record(s)");
        }
    }

    private function importSistemaOpcion(?array $data): void
    {
        if ($data === null) {
            return;
        }

        SistemaOpcion::updateOrCreate([], [
            'retention_period' => $data['retention_period'] ?? 'disabled',
        ]);
    }

    private function importDispatchSettings(?array $data): void
    {
        if ($data === null) {
            return;
        }

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
