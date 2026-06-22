<?php

namespace App\Livewire;

use App\Services\WhatsApp\WhatsAppSender;
use Illuminate\Support\Arr;
use Livewire\Component;

class WhatsAppConnectionTest extends Component
{
    public string $recipient = '';
    public string $body = 'Mensaje de prueba desde Clínica Dental Eugénia.';
    public string $mode = 'sandbox';
    public string $status = '';
    public string $statusType = 'neutral';
    public array $details = [];

    public function mount(): void
    {
        $this->recipient = '';
    }

    public function rules(): array
    {
        return [
            'recipient' => ['required', 'string', 'max:40'],
            'body' => ['required', 'string', 'max:500'],
            'mode' => ['required', 'in:sandbox,sender,service'],
        ];
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
        } catch (\Throwable $throwable) {
            $this->statusType = 'error';
            $this->status = $throwable->getMessage();
            $this->details = [];
        }
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
        $twilio = config('whatsapp.twilio', []);
        $from = (string) ($twilio['from'] ?? '');
        $messagingServiceSid = (string) ($twilio['messaging_service_sid'] ?? '');

        return [
            'provider' => 'twilio',
            'mode' => $preview['mode'],
            'request' => array_filter([
                'From' => $preview['mode'] === 'service' ? null : $this->normalizeWhatsAppAddress($from),
                'MessagingServiceSid' => $preview['mode'] === 'service' ? $messagingServiceSid : null,
                'To' => $this->normalizeWhatsAppRecipient($preview['recipient']),
                'Body' => $preview['body'],
            ], static fn ($value) => $value !== null && $value !== ''),
        ];
    }

    private function buildCloudApiPreviewPayload(array $preview): array
    {
        return [
            'provider' => 'cloud_api',
            'request' => [
                'messaging_product' => 'whatsapp',
                'to' => $this->normalizePhoneNumber($preview['recipient']),
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

    private function normalizeWhatsAppAddress(string $address): string
    {
        return str_starts_with($address, 'whatsapp:') ? $address : 'whatsapp:'.ltrim($address);
    }

    private function normalizeWhatsAppRecipient(string $recipient): string
    {
        $normalized = $this->normalizePhoneNumber($recipient);

        return $normalized !== '' ? 'whatsapp:'.$normalized : '';
    }

    private function normalizePhoneNumber(string $recipient): string
    {
        $digits = preg_replace('/\D+/', '', $recipient) ?? '';

        if ($digits === '') {
            return '';
        }

        if (str_starts_with(trim($recipient), '+')) {
            return '+'.$digits;
        }

        $countryCode = preg_replace('/\D+/', '', (string) config('whatsapp.default_country_code', '+34')) ?? '34';

        return '+'.$countryCode.$digits;
    }
}
