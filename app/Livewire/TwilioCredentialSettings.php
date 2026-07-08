<?php

namespace App\Livewire;

use App\Models\WhatsAppCredential;
use App\Models\WhatsAppSenderNumber;
use Livewire\Component;

class TwilioCredentialSettings extends Component
{
    public string $mode = 'sandbox';

    public string $api_key_sid = '';

    public string $api_key_secret = '';

    public string $status_callback_url = '';

    public string $status = '';

    public array $senderNumbers = [];

    public string $newName = '';

    public string $newPrefix = '+1';

    public string $newNumber = '';

    public function mount(): void
    {
        $credential = WhatsAppCredential::get();

        $this->mode = $credential->mode;
        $this->api_key_sid = $credential->api_key_sid ?? '';
        $this->api_key_secret = $credential->api_key_secret ?? '';
        $this->status_callback_url = $credential->resolveStatusCallbackUrl();
        $this->loadSenderNumbers();
    }

    private function loadSenderNumbers(): void
    {
        $credential = WhatsAppCredential::get();
        $this->senderNumbers = $credential->senderNumbers
            ->map(fn (WhatsAppSenderNumber $n) => [
                'id' => $n->id,
                'name' => $n->name ?? '',
                'prefix' => $n->prefix,
                'number' => $n->number,
                'selected' => $n->selected,
            ])
            ->toArray();
    }

    public function toggleMode(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $this->mode = $this->mode === 'sandbox' ? 'sender' : 'sandbox';

        $credential = WhatsAppCredential::get();
        $credential->update(['mode' => $this->mode]);

        $this->dispatch('modeChanged', value: $this->mode);
    }

    public function addSenderNumber(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $data = $this->validate([
            'newName' => ['nullable', 'string', 'max:100'],
            'newPrefix' => ['required', 'string', 'regex:/^\+\d{1,4}$/'],
            'newNumber' => ['required', 'string', 'digits_between:6,15'],
        ]);

        $credential = WhatsAppCredential::get();
        $hasAny = $credential->senderNumbers()->exists();

        $credential->senderNumbers()->create([
            'name' => $data['newName'] ?: null,
            'prefix' => $data['newPrefix'],
            'number' => $data['newNumber'],
            'selected' => ! $hasAny,
        ]);

        $this->newName = '';
        $this->newPrefix = '+1';
        $this->newNumber = '';
        $this->loadSenderNumbers();

        $this->dispatch('credentialsChanged');
    }

    public function removeSenderNumber(int $id): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $credential = WhatsAppCredential::get();
        $number = $credential->senderNumbers()->find($id);

        if ($number) {
            $wasSelected = $number->selected;
            $number->delete();

            if ($wasSelected) {
                $first = $credential->senderNumbers()->first();
                if ($first) {
                    $first->update(['selected' => true]);
                }
            }
        }

        $this->loadSenderNumbers();
        $this->dispatch('credentialsChanged');
    }

    public function selectSenderNumber(int $id): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $credential = WhatsAppCredential::get();
        $credential->senderNumbers()->update(['selected' => false]);
        $credential->senderNumbers()->where('id', $id)->update(['selected' => true]);

        $this->loadSenderNumbers();
        $this->dispatch('credentialsChanged');
    }

    public function save(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $data = $this->validate([
            'api_key_sid' => ['nullable', 'string'],
            'api_key_secret' => ['nullable', 'string'],
            'status_callback_url' => ['nullable', 'string', 'url'],
        ]);

        $credential = WhatsAppCredential::get();

        $updateData = [];

        if ($data['api_key_sid'] !== '') {
            $updateData['api_key_sid'] = $data['api_key_sid'];
        }

        if ($data['api_key_secret'] !== '') {
            $updateData['api_key_secret'] = $data['api_key_secret'];
        }

        if ($updateData !== []) {
            $credential->update($updateData);
        }

        $credential->update(['status_callback_url' => $data['status_callback_url'] ?: null]);

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
