<?php

namespace App\Livewire;

use App\Services\WhatsApp\WhatsAppSender;
use App\Traits\NormalizesPhone;
use Illuminate\Support\Arr;
use Livewire\Component;
use Throwable;

class WhatsAppConnectionTest extends Component
{
    use NormalizesPhone;

    public string $recipient = '';

    public string $body = 'Mensaje de prueba desde Clínica Dental Eugenia.';

    public string $mode = 'sandbox';

    public string $status = '';

    public string $statusType = 'neutral';

    public array $details = [];

    public function mount(): void
    {
        $this->recipient = '';
        $this->mode = $this->initialTwilioMode();
    }

    private function initialTwilioMode(): string
    {
        $configuredMode = strtolower(trim((string) config('whatsapp.twilio.mode', 'auto')));

        return in_array($configuredMode, ['auto', 'sandbox', 'sender', 'service'], true) ? $configuredMode : 'auto';
    }

    public function rules(): array
    {
        return [
            'recipient' => ['required', 'string', 'max:40'],
            'body' => ['required', 'string', 'max:500'],
            'mode' => ['required', 'in:auto,sandbox,sender,service'],
        ];
    }

    public function sendSavedRecipient(WhatsAppSender $sender): void
    {
        $savedRecipient = $sender->twilioTestRecipient();

        if (! $savedRecipient) {
            $this->statusType = 'error';
            $this->status = 'Define TWILIO_TEST_RECIPIENT para usar este acceso rápido.';
            $this->details = [];

            return;
        }

        $this->recipient = $savedRecipient;
        $this->sendTest($sender);
    }

    public function sendTest(WhatsAppSender $sender): void
    {
        $data = $this->validate();

        try {
            $result = $sender->sendTestMessage($data['recipient'], $data['body'], $data['mode']);

            $this->statusType = 'success';
            $this->status = 'Prueba enviada correctamente.';
            $this->details = [
                'provider' => $result['provider'],
                'message_id' => $result['message_id'],
                'to' => Arr::get($result, 'payload.to', $data['recipient']),
                'mode' => Arr::get($result, 'payload.mode', $data['mode']),
            ];
        } catch (Throwable $throwable) {
            $this->statusType = 'error';
            $this->status = $throwable->getMessage();
            $this->details = [];
        }
    }

    public function render()
    {
        return view('livewire.whatsapp-connection-test', [
            'previewPayload' => $this->buildPreviewPayload(),
        ]);
    }

    private function buildPreviewPayload(): array
    {
        $recipient = $this->recipient !== '' ? $this->recipient : (string) config('whatsapp.twilio.test_recipient', '');
        $preview = [
            'driver' => config('whatsapp.driver'),
            'mode' => $this->mode,
            'recipient' => $recipient,
            'body' => $this->body,
        ];

        return match (config('whatsapp.driver')) {
            'twilio' => $this->buildTwilioPreviewPayload($preview),
            'cloud_api' => $this->buildCloudApiPreviewPayload($preview),
            default => $this->buildLogPreviewPayload($preview),
        };
    }

    private function buildTwilioPreviewPayload(array $preview): array
    {
        $mode = $preview['mode'];
        $sender = new WhatsAppSender;
        $resolvedMode = $sender->resolveTwilioMode($mode);

        return [
            'provider' => 'twilio',
            'mode' => $mode,
            'resolved_mode' => $resolvedMode,
            'request' => $sender->buildTwilioPreviewRequest($preview['recipient'], $preview['body'], $mode),
        ];
    }

    private function buildCloudApiPreviewPayload(array $preview): array
    {
        return [
            'provider' => 'cloud_api',
            'request' => [
                'messaging_product' => 'whatsapp',
                'to' => static::normalizePhone($preview['recipient']),
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => $preview['body'],
                ],
            ],
        ];
    }

    private function buildLogPreviewPayload(array $preview): array
    {
        return [
            'provider' => 'log',
            'request' => [
                'recipient' => $preview['recipient'],
                'body' => $preview['body'],
            ],
        ];
    }
}
