<?php

namespace App\Livewire\Settings;

use App\Models\AppSetting;
use App\Models\WhatsAppCredential;
use App\Services\WhatsApp\WhatsAppSender;
use Livewire\Component;

class SettingsOverview extends Component
{
    protected $listeners = ['modeChanged' => '$refresh', 'credentialsChanged' => '$refresh'];

    public function render()
    {
        $credential = WhatsAppCredential::get();
        $sender = app(WhatsAppSender::class);
        $settings = AppSetting::get();

        return view('settings.settings-overview', [
            'driver' => $credential->resolveDriver(),
            'credential' => $credential,
            'resolvedMode' => $sender->resolveTwilioMode(),
            'twilioReminderContentSid' => $sender->twilioContentSid(WhatsAppSender::TEMPLATE_SCOPE_APPOINTMENT_REMINDER),
            'twilioCreatedContentSid' => $sender->twilioContentSid(WhatsAppSender::TEMPLATE_SCOPE_APPOINTMENT_CREATED),
            'twilioChangedContentSid' => $sender->twilioContentSid(WhatsAppSender::TEMPLATE_SCOPE_APPOINTMENT_CHANGED),
            'twilioUsesTemplate' => $credential->resolveDriver() === 'twilio',
            'hasSpecificTwilioTemplateAssignments' => (bool) ($settings->twilio_template_appointment_reminder_id || $settings->twilio_template_appointment_created_id || $settings->twilio_template_appointment_changed_id),
        ]);
    }
}
