<?php

namespace App\Livewire\Settings;

use App\Models\WhatsAppCredential;
use App\Models\WhatsAppSenderNumber;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class TwilioCredentialSettings extends Component
{
    public string $mode = 'sandbox';

    public string $api_key_sid = '';

    public string $api_key_secret = '';

    public string $status_callback_url = '';

    public bool $webhook_enabled = true;

    public int $poll_interval = 10;

    public string $status = '';

    public array $senderNumbers = [];

    public string $newName = '';

    public string $newPrefix = '+1';

    public string $newNumber = '';

    public ?int $senderNumberPendingDeletion = null;

    public function mount(): void
    {
        $credential = WhatsAppCredential::get();

        $this->mode = $credential->mode;
        $this->api_key_sid = $credential->api_key_sid ?? '';
        $this->api_key_secret = $credential->api_key_secret ?? '';
        $this->status_callback_url = $credential->resolveStatusCallbackUrl();
        $this->webhook_enabled = $credential->webhook_enabled ?? true;
        $this->poll_interval = $credential->poll_interval ?? 10;
        $this->loadSenderNumbers();
    }

    private function loadSenderNumbers(): void
    {
        $credential = WhatsAppCredential::get();
        $this->senderNumbers = $credential->senderNumbers()
            ->orderBy('id')
            ->get()
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
        $this->senderNumberPendingDeletion = null;
    }

    public function confirmRemoveSenderNumber(int $id): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $this->senderNumberPendingDeletion = $id;
    }

    public function cancelRemoveSenderNumber(): void
    {
        $this->senderNumberPendingDeletion = null;
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
            'status_callback_url' => ['nullable', 'string', 'max:500'],
            'poll_interval' => ['required', 'integer', 'min:5', 'max:60'],
        ]);

        $callbackUrl = $data['status_callback_url'] ?: null;

        if ($callbackUrl !== null) {
            $urlError = $this->validateCallbackUrl($callbackUrl);
            if ($urlError !== null) {
                $this->status = $urlError;
                $this->dispatch('toast', message: $urlError, type: 'error');

                return;
            }
        }

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

        $credential->update([
            'status_callback_url' => $callbackUrl,
            'webhook_enabled' => $this->webhook_enabled,
            'poll_interval' => $data['poll_interval'],
        ]);

        $this->status = 'Credenciales guardadas correctamente.';
        $this->dispatch('toast', message: 'Credenciales guardadas correctamente.', type: 'success');

        $this->dispatch('credentialsChanged');
    }

    public function testWebhook(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $credential = WhatsAppCredential::get();
        $callbackUrl = $credential->resolveStatusCallbackUrl();

        if ($callbackUrl === '' || $callbackUrl === null) {
            $this->status = 'No hay Callback URL configurada.';
            $this->dispatch('toast', message: 'Configura una Callback URL primero.', type: 'error');

            return;
        }

        try {
            $response = Http::timeout(10)
                ->post($callbackUrl, [
                    'MessageSid' => 'SM'.strtoupper(bin2hex(random_bytes(16))),
                    'AccountSid' => $credential->resolveAccountSid() ?? '',
                    'From' => 'whatsapp:+34600000000',
                    'To' => 'whatsapp:+15559355880',
                    'Body' => 'Test webhook message',
                    'NumMedia' => '0',
                    'ButtonText' => 'Confirmar',
                    'ButtonPayload' => 'confirmarcita',
                    'WaId' => '34600000000',
                ]);

            $body = $response->body();
            $statusCode = $response->status();

            if ($response->successful()) {
                $this->status = "HTTP {$statusCode}\n".$body;
                $this->dispatch('toast', message: "Webhook OK (HTTP {$statusCode})", type: 'success');
            } else {
                $this->status = "HTTP {$statusCode} error\n".$body;
                $this->dispatch('toast', message: "Webhook respondió HTTP {$statusCode}", type: 'error');
            }
        } catch (\Throwable $e) {
            $this->status = "Error: {$e->getMessage()}";
            $this->dispatch('toast', message: 'Error al conectar con el webhook.', type: 'error');
        }
    }

    private function validateCallbackUrl(string $url): ?string
    {
        if (str_contains($url, ' ')) {
            return 'La URL no puede contener espacios.';
        }

        $parsed = parse_url($url);

        if (! is_array($parsed) || empty($parsed['scheme']) || empty($parsed['host'])) {
            return 'La URL no es válida. Formato esperado: https://dominio.com/ruta';
        }

        if (! in_array($parsed['scheme'], ['http', 'https'], true)) {
            return 'El esquema debe ser http:// o https://.';
        }

        $host = $parsed['host'];

        if (strlen($host) < 3) {
            return 'El dominio de la URL es demasiado corto.';
        }

        if (! preg_match('/^[a-zA-Z0-9]([a-zA-Z0-9\-]*[a-zA-Z0-9])?(\.[a-zA-Z0-9]([a-zA-Z0-9\-]*[a-zA-Z0-9])?)*\.[a-zA-Z]{2,}$/', $host)) {
            return 'El dominio de la URL no parece válido.';
        }

        $path = $parsed['path'] ?? '';
        if ($path !== '' && ! str_starts_with($path, '/')) {
            return 'La ruta de la URL debe empezar con /.';
        }

        return null;
    }

    public function render()
    {
        $credential = WhatsAppCredential::get();
        $pendingSenderNumber = collect($this->senderNumbers)
            ->firstWhere('id', $this->senderNumberPendingDeletion);

        $hasAuthToken = filled($credential->resolveAuthToken());

        return view('settings.twilio-credential-settings', [
            'credential' => $credential,
            'pendingSenderNumber' => $pendingSenderNumber,
            'apiKeySidLabel' => $hasAuthToken ? 'API Key SID (no necesario)' : 'API Key SID',
            'apiKeySecretLabel' => $hasAuthToken ? 'API Key Secret (no necesario)' : 'API Key Secret',
        ]);
    }
}
