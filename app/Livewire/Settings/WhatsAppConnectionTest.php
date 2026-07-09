<?php

namespace App\Livewire\Settings;

use App\Models\TwilioContentTemplate;
use App\Models\WhatsAppCredential;
use App\Services\WhatsApp\WhatsAppSender;
use App\Traits\NormalizesPhone;
use Illuminate\Support\Arr;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class WhatsAppConnectionTest extends Component
{
    use NormalizesPhone;

    public string $recipient = '';

    public string $body = 'Mensaje de prueba desde ... ';

    public string $mode = 'sandbox';

    public string $testType = 'text';

    public string $templateId = '';

    public string $status = '';

    public string $statusType = 'neutral';

    public int $statusNonce = 0;

    public array $details = [];

    public function mount(): void
    {
        $this->recipient = (string) $this->previewCredential()->resolveTestRecipient();
        $this->mode = $this->initialTwilioMode();
        $this->templateId = (string) (TwilioContentTemplate::selectedOrFirst()?->id ?? '');
    }

    #[On('credentialsChanged')]
    public function refreshPreview(): void
    {
        //
    }

    #[On('templateChanged')]
    public function refreshTemplatePreview(): void
    {
        $this->templateId = (string) (TwilioContentTemplate::selectedOrFirst()?->id ?? '');
    }

    public function updatedTestType(string $value): void
    {
        if ($value !== 'template') {
            $this->templateId = '';

            return;
        }

        if ($this->templateId === '') {
            $this->templateId = (string) (TwilioContentTemplate::selectedOrFirst()?->id ?? '');
        }
    }

    private function initialTwilioMode(): string
    {
        $configuredMode = strtolower(trim((string) $this->previewCredential()->resolveMode()));

        return in_array($configuredMode, ['auto', 'sandbox', 'sender'], true) ? $configuredMode : 'auto';
    }

    public function rules(): array
    {
        return [
            'recipient' => ['required', 'string', 'max:40'],
            'body' => ['required', 'string', 'max:500'],
            'mode' => ['required', 'in:auto,sandbox,sender'],
            'testType' => ['required', 'in:text,template'],
            'templateId' => ['exclude_unless:testType,template', 'required', 'integer', 'exists:twilio_content_templates,id'],
        ];
    }

    public function sendSavedRecipient(WhatsAppSender $sender): void
    {
        $credential = WhatsAppCredential::get();
        $savedRecipient = $credential->resolveTestRecipient();

        if ($savedRecipient === null || trim($savedRecipient) === '') {
            $this->setStatus('error', 'No hay destinatario de prueba guardado. Configura test_recipient en credenciales.');
            $this->details = [];

            return;
        }

        $this->recipient = $savedRecipient;
        $this->testType = 'text';
        $this->templateId = '';

        if ($this->recipientIsSenderNumber($this->recipient)) {
            $this->setStatus('error', 'No puedes enviar una prueba a un número que ya está configurado como remitente.');
            $this->details = [];

            return;
        }

        $this->sendTest($sender);
    }

    public function sendTest(WhatsAppSender $sender): void
    {
        $data = $this->validate();

        if ($this->recipientIsSenderNumber($data['recipient'])) {
            $this->addError('recipient', 'No puedes enviar una prueba a un número que ya está configurado como remitente.');
            $this->setStatus('error', 'No puedes enviar una prueba a un número que ya está configurado como remitente.');
            $this->details = [];

            return;
        }

        try {
            $templateId = $data['testType'] === 'template' ? (int) $data['templateId'] : null;

            if ($data['testType'] === 'template' && ! $templateId) {
                $this->addError('templateId', 'Selecciona una plantilla para enviar la prueba.');
                $this->setStatus('error', 'Selecciona una plantilla para enviar la prueba.');
                $this->details = [];

                return;
            }

            $result = $sender->sendTestMessage(
                $data['recipient'],
                $data['body'],
                $data['mode'],
                $data['testType'] === 'template',
                $templateId,
            );

            $this->setStatus('success', 'Prueba enviada correctamente.');
            $this->details = [
                'provider' => $result['provider'],
                'message_id' => $result['message_id'],
                'to' => Arr::get($result, 'payload.to', $data['recipient']),
                'mode' => Arr::get($result, 'payload.mode', $data['mode']),
            ];
        } catch (Throwable $throwable) {
            $this->setStatus('error', $throwable->getMessage());
            $this->details = [];
        }
    }

    public function render()
    {
        return view('settings.whatsapp-connection-test', [
            'previewPayload' => $this->buildPreviewPayload(),
            'templates' => TwilioContentTemplate::query()->orderBy('nombre')->get(),
        ]);
    }

    private function buildPreviewPayload(): array
    {
        $credential = $this->previewCredential();
        $selectedNumber = $credential->selectedSenderNumber();
        $recipient = $this->recipient !== '' ? $this->recipient : ($selectedNumber->full_number ?? '');
        $preview = [
            'driver' => $credential->resolveDriver(),
            'mode' => $this->mode,
            'recipient' => $recipient,
            'body' => $this->body,
            'test_type' => $this->testType,
            'template_id' => $this->templateId !== '' ? (int) $this->templateId : null,
        ];

        return match ($credential->resolveDriver()) {
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
        $forceTemplate = ($preview['test_type'] ?? 'text') === 'template';
        $templateId = $preview['template_id'] ?? null;

        return [
            'provider' => 'twilio',
            'mode' => $mode,
            'test_type' => $preview['test_type'] ?? 'text',
            'resolved_mode' => $resolvedMode,
            'request' => $sender->buildTwilioPreviewRequest($preview['recipient'], $preview['body'], $mode, $forceTemplate, $templateId),
        ];
    }

    private function buildCloudApiPreviewPayload(array $preview): array
    {
        return [
            'provider' => 'cloud_api',
            'request' => [
                'messaging_product' => 'whatsapp',
                'to' => static::normalizeInternationalPhone($preview['recipient']),
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

    private function recipientIsSenderNumber(string $recipient): bool
    {
        $normalizedRecipient = static::normalizeInternationalPhone($recipient);

        return $this->previewCredential()
            ->senderNumbers()
            ->get()
            ->contains(function ($senderNumber) use ($normalizedRecipient): bool {
                return static::normalizeInternationalPhone($senderNumber->full_number) === $normalizedRecipient;
            });
    }

    private function previewCredential(): WhatsAppCredential
    {
        if (WhatsAppCredential::query()->where('selected', true)->exists()) {
            return WhatsAppCredential::query()->where('selected', true)->firstOrFail();
        }

        if (WhatsAppCredential::query()->exists()) {
            return WhatsAppCredential::query()->firstOrFail();
        }

        return new WhatsAppCredential([
            'mode' => config('whatsapp.twilio.mode', 'sandbox'),
            'selected' => false,
        ]);
    }

    private function setStatus(string $type, string $message): void
    {
        $this->statusType = $type;
        $this->status = $message;
        $this->statusNonce++;
    }
}
