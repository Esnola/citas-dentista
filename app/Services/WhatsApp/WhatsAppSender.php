<?php

namespace App\Services\WhatsApp;

use App\Models\TwilioContentTemplate;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppMessage;
use App\Traits\NormalizesPhone;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use JsonException;
use RuntimeException;
use Throwable;

class WhatsAppSender
{
    use NormalizesPhone;

    private const TWILIO_AUTO_MODE = 'auto';

    private const TWILIO_SANDBOX_MODE = 'sandbox';

    private const TWILIO_SENDER_MODE = 'sender';

    /**
     * @return array{provider:string,message_id:?string,payload:array,raw:array}
     *
     * @throws RequestException
     */
    public function send(WhatsAppMessage $message): array
    {
        try {
            return match (WhatsAppCredential::get()->resolveDriver()) {
                'twilio' => $this->sendViaTwilio($message),
                'cloud_api' => $this->sendViaCloudApi($message),
                'log' => $this->sendViaLog($message),
                default => throw new RuntimeException('Unsupported WhatsApp driver: '.WhatsAppCredential::get()->resolveDriver()),
            };
        } catch (Throwable $throwable) {
            Log::channel('whatsapp_error')->error('WhatsApp send failed', [
                'message_id' => $message->id,
                'appointment_id' => $message->appointment_id,
                'client_id' => $message->client_id,
                'telefono' => $message->telefono,
                'error' => $throwable->getMessage(),
            ]);

            throw $throwable;
        }
    }

    /**
     * Send a one-off test message without persisting a database record.
     *
     * @return array{provider:string,message_id:?string,payload:array,raw:array}
     */
    public function sendTestMessage(string $recipient, string $body, ?string $mode = null, bool $forceTemplate = false, ?int $templateId = null): array
    {
        return match (WhatsAppCredential::get()->resolveDriver()) {
            'twilio' => $this->sendTestViaTwilio($recipient, $body, $mode, $forceTemplate, $templateId),
            'cloud_api' => $this->sendTestViaCloudApi($recipient, $body),
            'log' => $this->sendTestViaLog($recipient, $body),
            default => throw new RuntimeException('Unsupported WhatsApp driver: '.WhatsAppCredential::get()->resolveDriver()),
        };
    }

    /**
     * @return array{provider:string,message_id:?string,payload:array,raw:array}
     */
    private function sendViaLog(WhatsAppMessage $message): array
    {
        $payload = $this->buildTextPayload($message);

        Log::info('WhatsApp message dispatched', [
            'provider' => 'log',
            'recipient' => $payload['to'],
            'name' => $message->full_name,
            'scheduled_for' => $message->scheduled_for?->toDateTimeString(),
            'message' => $message->message,
        ]);

        return [
            'provider' => 'log',
            'message_id' => null,
            'payload' => $payload,
            'raw' => [],
        ];
    }

    /**
     * @return array{provider:string,message_id:?string,payload:array,raw:array}
     */
    private function sendTestViaLog(string $recipient, string $body): array
    {
        $payload = [
            'to' => $recipient,
            'body' => $body,
        ];

        Log::info('WhatsApp test message dispatched', [
            'provider' => 'log',
            'recipient' => $recipient,
            'message' => $body,
        ]);

        return [
            'provider' => 'log',
            'message_id' => null,
            'payload' => $payload,
            'raw' => [],
        ];
    }

    /**
     * @return array{provider:string,message_id:?string,payload:array,raw:array}
     */
    private function sendViaTwilio(WhatsAppMessage $message): array
    {
        return $this->sendTwilioRequest($message->telefono, $message->message, message: $message);
    }

    /**
     * @return array{provider:string,message_id:?string,payload:array,raw:array}
     */
    private function sendTestViaTwilio(string $recipient, string $body, ?string $mode = null, bool $forceTemplate = false, ?int $templateId = null): array
    {
        $messageMode = strtolower(trim((string) WhatsAppCredential::get()->resolveMessageMode()));
        $usesTemplate = $forceTemplate || $messageMode === 'template';

        if ($usesTemplate) {
            return $this->sendTwilioRequest(
                $recipient,
                $body,
                $mode,
                $forceTemplate,
                $templateId,
                $this->buildFakeTemplateMessage($recipient, $body),
            );
        }

        return $this->sendTwilioRequest($recipient, $body, $mode);
    }

    /**
     * @return array{provider:string,message_id:?string,payload:array,raw:array}
     */
    private function sendTwilioRequest(
        string $recipient,
        string $body,
        ?string $mode = null,
        bool $forceTemplate = false,
        ?int $templateId = null,
        ?WhatsAppMessage $message = null,
    ): array {
        $credential = WhatsAppCredential::get();
        $accountSid = $credential->resolveAccountSid();
        [$username, $password] = $this->twilioApiCredentials($credential);

        if (! $accountSid || ! $username || ! $password) {
            throw new RuntimeException('Twilio credentials are not configured.');
        }

        [$payload, $requestPayload] = $this->buildTwilioPayload($recipient, $body, $mode, $message, true, $forceTemplate, $templateId);

        $response = Http::baseUrl('https://api.twilio.com')
            ->acceptJson()
            ->asForm()
            ->withBasicAuth($username, $password)
            ->retry([100, 500, 1000])
            ->timeout($credential->resolveTimeout())
            ->connectTimeout($credential->resolveConnectTimeout())
            ->post('/2010-04-01/Accounts/'.$accountSid.'/Messages.json', $requestPayload)
            ->throw()
            ->json();

        return [
            'provider' => 'twilio',
            'message_id' => data_get($response, 'sid'),
            'payload' => $payload,
            'raw' => $response,
        ];
    }

    /**
     * @return array{0:?string,1:?string}
     */
    private function twilioApiCredentials(WhatsAppCredential $credential): array
    {
        $dbApiKeySid = $credential->resolveApiKeySid();
        $dbApiKeySecret = $credential->resolveApiKeySecret();

        // Primero intenta API Key/Secret; si no existen, cae a Account SID/Auth Token.
        if (filled($dbApiKeySid) && filled($dbApiKeySecret)) {
            return [$dbApiKeySid, $dbApiKeySecret];
        }

        return [$credential->resolveAccountSid(), $credential->resolveAuthToken()];
    }

    /**
     * @return array<string, mixed>
     */
    public function buildTwilioPreviewRequest(string $recipient, string $body, ?string $mode = null, bool $forceTemplate = false, ?int $templateId = null): array
    {
        return $this->buildTwilioPayload($recipient, $body, $mode, null, false, $forceTemplate, $templateId)[1];
    }

    /**
     * @return array{0:array,1:array}
     */
    private function buildTwilioPayload(
        string $recipient,
        string $body,
        ?string $mode = null,
        ?WhatsAppMessage $message = null,
        bool $validateConfiguration = true,
        bool $forceTemplate = false,
        ?int $templateId = null,
    ): array {
        $credential = WhatsAppCredential::get();
        $from = $credential->resolveFrom();
        $template = $this->twilioContentTemplate($templateId);
        $contentSid = $template?->content_sid ?: $this->twilioContentSid();
        $resolvedMode = $this->resolveTwilioMode($mode);
        $messageMode = strtolower(trim($credential->resolveMessageMode()));
        $usesTemplate = $forceTemplate || $messageMode === 'template';

        if ($validateConfiguration && in_array($resolvedMode, [self::TWILIO_SANDBOX_MODE, self::TWILIO_SENDER_MODE], true) && ! $from) {
            throw new RuntimeException('Twilio WhatsApp sender is not configured.');
        }

        if ($validateConfiguration && $usesTemplate && ! $contentSid) {
            throw new RuntimeException('No hay una plantilla de Twilio seleccionada. Debe existir una fila con seleccionada = 1 en twilio_content_templates, o un TWILIO_CONTENT_SID como respaldo.');
        }

        $contentVariables = $usesTemplate ? $this->twilioContentVariables($message, $template) : [];

        $payload = [
            'mode' => $resolvedMode,
            'from' => $from ? $this->normalizeWhatsAppAddress($from) : null,
            'to' => $this->normalizeWhatsAppRecipient($recipient),
            'body' => $body,
            'content_sid' => $usesTemplate ? $contentSid : null,
            'content_variables' => $contentVariables,
        ];

        $requestPayload = array_filter([
            'From' => $payload['from'],
            'To' => $payload['to'],
            'Body' => $usesTemplate ? null : $payload['body'],
            'ContentSid' => $payload['content_sid'],
            'ContentVariables' => $contentVariables !== [] ? $this->jsonEncode($contentVariables) : null,
            'StatusCallback' => $this->twilioStatusCallbackUrl(),
        ], static fn ($value) => $value !== null && $value !== '');

        return [$payload, $requestPayload];
    }

    public function resolveTwilioMode(?string $mode = null): string
    {
        $credential = WhatsAppCredential::get();
        $requestedMode = strtolower(trim($mode ?: $credential->resolveMode()));

        if (! in_array($requestedMode, $this->twilioModes(), true)) {
            throw new RuntimeException('Unsupported Twilio WhatsApp mode: '.$requestedMode);
        }

        if ($requestedMode !== self::TWILIO_AUTO_MODE) {
            return $requestedMode;
        }

        $from = (string) ($credential->resolveFrom() ?? '');

        if ($from !== '' && $this->normalizeWhatsAppAddress($from) === 'whatsapp:+14155238886') {
            return self::TWILIO_SANDBOX_MODE;
        }

        return self::TWILIO_SENDER_MODE;
    }

    /**
     * @return list<string>
     */
    private function twilioModes(): array
    {
        return [
            self::TWILIO_AUTO_MODE,
            self::TWILIO_SANDBOX_MODE,
            self::TWILIO_SENDER_MODE,
        ];
    }

    /**
     * @return array<string, string>
     */
    private function twilioContentVariables(?WhatsAppMessage $message, ?TwilioContentTemplate $template = null): array
    {
        Carbon::setLocale('es');
        $template ??= TwilioContentTemplate::selectedOrFirst();
        $variables = $template?->content_variables ?? [];

        if (! is_array($variables)) {
            return [];
        }

        $scheduledFor = $message?->appointment?->scheduledFor() ?? $message?->scheduled_for;
        $fakeScheduledFor = now()->addDay()->setTime(10, 30);
        $scheduledValue = $scheduledFor ?? $fakeScheduledFor;
        $replacements = [
            '[NOMBRE]' => (string) ($message?->nombre ?? 'Ana'),
            '[APELLIDOS]' => (string) ($message?->apellidos ?? 'López'),
            '[TELEFONO]' => (string) ($message?->telefono ?? '+34600123123'),
            '[DIA]' => $scheduledValue?->translatedFormat('l j \d\e F') ?? '',
            '[FECHA]' => $scheduledValue?->translatedFormat('l j \d\e F') ?? '',
            '[HORA]' => $scheduledValue?->format('H:i') ?? '',
            '[MENSAJE]' => $message?->message ?? 'Mensaje de prueba',
        ];

        return collect($variables)
            ->mapWithKeys(fn (mixed $value, int|string $key): array => [
                (string) $key => strtr((string) $value, $replacements),
            ])
            ->all();
    }

    private function buildFakeTemplateMessage(string $recipient, string $body): WhatsAppMessage
    {
        return new WhatsAppMessage([
            'nombre' => 'Ana',
            'apellidos' => 'López',
            'telefono' => $recipient,
            'scheduled_for' => now()->addDay()->setTime(10, 30),
            'message' => $body,
        ]);
    }

    /**
     * @param  array<string, string>  $value
     */
    private function jsonEncode(array $value): string
    {
        try {
            return json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        } catch (JsonException $exception) {
            throw new RuntimeException('Twilio content variables could not be encoded.', previous: $exception);
        }
    }

    /**
     * @return array{provider:string,message_id:?string,payload:array,raw:array}
     *
     * @throws RequestException
     */
    private function sendViaCloudApi(WhatsAppMessage $message): array
    {
        $credential = WhatsAppCredential::get();
        $phoneNumberId = $credential->resolveCloudApiPhoneNumberId();
        $accessToken = $credential->resolveCloudApiAccessToken();

        if (! $phoneNumberId || ! $accessToken) {
            throw new RuntimeException('WhatsApp Cloud API credentials are not configured.');
        }

        $payload = $this->buildTextPayload($message);

        $response = Http::baseUrl(rtrim($credential->resolveCloudApiBaseUrl(), '/'))
            ->acceptJson()
            ->asJson()
            ->withToken($accessToken)
            ->timeout($credential->resolveCloudApiTimeout())
            ->connectTimeout($credential->resolveConnectTimeout())
            ->post(sprintf('/%s/%s/messages', $credential->resolveCloudApiVersion(), $phoneNumberId), $payload)
            ->throw()
            ->json();

        return [
            'provider' => 'cloud_api',
            'message_id' => data_get($response, 'messages.0.id'),
            'payload' => $payload,
            'raw' => $response,
        ];
    }

    /**
     * @return array{provider:string,message_id:?string,payload:array,raw:array}
     *
     * @throws RequestException
     */
    private function sendTestViaCloudApi(string $recipient, string $body): array
    {
        $credential = WhatsAppCredential::get();
        $phoneNumberId = $credential->resolveCloudApiPhoneNumberId();
        $accessToken = $credential->resolveCloudApiAccessToken();

        if (! $phoneNumberId || ! $accessToken) {
            throw new RuntimeException('WhatsApp Cloud API credentials are not configured.');
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $this->normalizeInternationalPhone($recipient),
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $body,
            ],
        ];

        $response = Http::baseUrl(rtrim($credential->resolveCloudApiBaseUrl(), '/'))
            ->acceptJson()
            ->asJson()
            ->withToken($accessToken)
            ->timeout($credential->resolveCloudApiTimeout())
            ->connectTimeout($credential->resolveConnectTimeout())
            ->post(sprintf('/%s/%s/messages', $credential->resolveCloudApiVersion(), $phoneNumberId), $payload)
            ->throw()
            ->json();

        return [
            'provider' => 'cloud_api',
            'message_id' => data_get($response, 'messages.0.id'),
            'payload' => $payload,
            'raw' => $response,
        ];
    }

    private function buildTextPayload(WhatsAppMessage $message): array
    {
        $body = $message->message;

        return [
            'messaging_product' => 'whatsapp',
            'to' => $message->normalizedPhone(),
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $body,
            ],
        ];
    }

    public function twilioContentSid(): ?string
    {
        return TwilioContentTemplate::selectedContentSid()
            ?: WhatsAppCredential::get()->resolveContentSid();
    }

    public function twilioContentTemplate(?int $templateId = null): ?TwilioContentTemplate
    {
        if ($templateId) {
            return TwilioContentTemplate::query()->find($templateId);
        }

        return TwilioContentTemplate::selectedOrFirst();
    }

    private function twilioStatusCallbackUrl(): string
    {
        $configuredUrl = WhatsAppCredential::get()->resolveStatusCallbackUrl();

        return $configuredUrl !== ''
            ? $configuredUrl
            : route('webhooks.twilio.whatsapp-status', absolute: true);
    }
}
