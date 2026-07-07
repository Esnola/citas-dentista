<?php

namespace App\Livewire;

use App\Models\WhatsAppCredential;
use Livewire\Component;

class TwilioCredentialSettings extends Component
{
    public string $mode = 'sandbox';

    public string $from_number = '';

    public string $test_recipient = '';

    public string $api_key_sid = '';

    public string $api_key_secret = '';

    public string $status = '';

    public function mount(): void
    {
        $credential = WhatsAppCredential::get();

        $this->mode = $credential->mode;
        $this->from_number = $credential->from_number ?? '';
        $this->test_recipient = $credential->test_recipient ?? '';
        $this->api_key_sid = $credential->api_key_sid ?? '';
        $this->api_key_secret = $credential->api_key_secret ?? '';
    }

    public function save(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $data = $this->validate([
            'mode' => ['required', 'string', 'in:sandbox,sender'],
            'from_number' => ['nullable', 'string'],
            'test_recipient' => ['nullable', 'string'],
            'api_key_sid' => ['nullable', 'string'],
            'api_key_secret' => ['nullable', 'string'],
        ]);

        $credential = WhatsAppCredential::get();

        $updateData = [
            'mode' => $data['mode'],
            'from_number' => $data['from_number'] ?: null,
            'test_recipient' => $data['test_recipient'] ?: null,
        ];

        if ($data['api_key_sid'] !== '') {
            $updateData['api_key_sid'] = $data['api_key_sid'];
        }

        if ($data['api_key_secret'] !== '') {
            $updateData['api_key_secret'] = $data['api_key_secret'];
        }

        $credential->update($updateData);

        $this->status = 'Credenciales guardadas correctamente.';
        $this->dispatch('credentialsChanged');
    }

    public function render()
    {
        return view('livewire.twilio-credential-settings', [
            'credential' => WhatsAppCredential::get(),
        ]);
    }
}
