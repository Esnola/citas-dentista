<?php

namespace Database\Seeders;

use App\Models\AppointmentReminderPreference;
use App\Models\AppSetting;
use App\Models\WhatsAppCredential;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAppSetting();
        $this->seedReminderPreferences();
        $this->seedCredential();
        $this->seedTemplates();
    }

    private function seedAppSetting(): void
    {
        AppSetting::updateOrCreate([], [
            'retention_period' => 'disabled',
            'dispatch_enabled' => true,
            'dispatch_hours' => ['09:00', '12:00', '15:00'],
        ]);
    }

    private function seedReminderPreferences(): void
    {
        $leadDays = array_keys(AppointmentReminderPreference::leadDayOptions());

        foreach ($leadDays as $leadDaysValue) {
            AppointmentReminderPreference::updateOrCreate(
                ['channel' => AppointmentReminderPreference::CHANNEL_WHATSAPP, 'lead_days' => $leadDaysValue],
                ['enabled' => true],
            );

            AppointmentReminderPreference::updateOrCreate(
                ['channel' => AppointmentReminderPreference::CHANNEL_EMAIL, 'lead_days' => $leadDaysValue],
                ['enabled' => false],
            );
        }
    }

    private function seedCredential(): void
    {
        WhatsAppCredential::firstOrCreate(
            ['selected' => true],
            ['mode' => 'sandbox'],
        );
    }

    private function seedTemplates(): void
    {
        $this->call(TwilioContentTemplateSeeder::class);
    }
}
