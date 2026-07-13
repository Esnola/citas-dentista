<?php

namespace App\Console\Commands;

use App\Models\AppointmentReminderPreference;
use App\Models\AppSetting;
use App\Models\TwilioContentTemplate;
use App\Models\WhatsAppCredential;
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

        if (! in_array($version, [1, 2], true)) {
            $this->error("Unsupported backup version: {$version}");

            return self::FAILURE;
        }

        $settings = $data['settings'] ?? [];

        if (! $this->option('force')) {
            $this->warn('The following settings will be imported:');
            $this->previewChanges($settings, $version);

            if (! $this->confirm('Apply these changes?')) {
                $this->info('Import cancelled.');

                return self::SUCCESS;
            }
        }

        DB::transaction(function () use ($settings, $version): void {
            if ($version === 1) {
                $this->importV1($settings);
            } else {
                $this->importV2($settings);
            }
        });

        $this->info('Settings imported successfully.');

        return self::SUCCESS;
    }

    private function importV1(array $settings): void
    {
        // v1: sistema_opciones + whatsapp_dispatch_settings → merged into app_settings
        $retentionPeriod = 'disabled';
        $dispatchEnabled = true;
        $dispatchHours = ['09:00', '12:00', '15:00'];

        if (! empty($settings['sistema_opciones'])) {
            $retentionPeriod = $settings['sistema_opciones']['retention_period'] ?? 'disabled';
        }

        if (! empty($settings['whatsapp_dispatch_settings'])) {
            $dispatchEnabled = $settings['whatsapp_dispatch_settings']['enabled'] ?? true;
            $dispatchHours = $settings['whatsapp_dispatch_settings']['hours'] ?? $dispatchHours;
        }

        AppSetting::updateOrCreate([], [
            'retention_period' => $retentionPeriod,
            'dispatch_enabled' => $dispatchEnabled,
            'dispatch_hours' => $dispatchHours,
            'twilio_template_appointment_reminder_id' => null,
            'twilio_template_appointment_created_id' => null,
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
                'twilio_template_appointment_reminder_id' => $data['twilio_template_appointment_reminder_id'] ?? null,
                'twilio_template_appointment_created_id' => $data['twilio_template_appointment_created_id'] ?? null,
            ]);
        }

        $this->importReminderPreferences($settings['appointment_reminder_preferences'] ?? []);
        $this->importCredentials($settings['whatsapp_credentials'] ?? []);
        $this->importSenderNumbers($settings['whatsapp_sender_numbers'] ?? []);
        $this->importTemplates($settings['twilio_content_templates'] ?? []);
    }

    private function previewChanges(array $settings, int $version): void
    {
        if ($version === 1) {
            if (! empty($settings['sistema_opciones'])) {
                $this->line('  - sistema_opciones: retention_period = '.$settings['sistema_opciones']['retention_period']);
            }
            if (! empty($settings['whatsapp_dispatch_settings'])) {
                $dispatch = $settings['whatsapp_dispatch_settings'];
                $this->line('  - whatsapp_dispatch_settings: enabled = '.($dispatch['enabled'] ? 'true' : 'false').', hours = '.json_encode($dispatch['hours']));
            }
        } else {
            if (! empty($settings['app_settings'])) {
                $app = $settings['app_settings'];
                $this->line('  - app_settings: retention = '.$app['retention_period'].', dispatch = '.($app['dispatch_enabled'] ? 'on' : 'off').', reminder_template = '.($app['twilio_template_appointment_reminder_id'] ?? 'null').', created_template = '.($app['twilio_template_appointment_created_id'] ?? 'null'));
            }
        }

        foreach (['appointment_reminder_preferences', 'whatsapp_credentials', 'whatsapp_sender_numbers', 'twilio_content_templates'] as $key) {
            if (! empty($settings[$key])) {
                $this->line("  - {$key}: ".count($settings[$key]).' record(s)');
            }
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
