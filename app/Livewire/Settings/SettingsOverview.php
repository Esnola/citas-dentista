<?php

namespace App\Livewire\Settings;

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

        return view('settings.settings-overview', [
            'driver' => config('whatsapp.driver'),
            'credential' => $credential,
            'resolvedMode' => $sender->resolveTwilioMode(),
            'twilioContentSid' => $sender->twilioContentSid(),
            'twilioUsesTemplate' => config('whatsapp.message_mode') === 'template',
        ]);
    }
}
