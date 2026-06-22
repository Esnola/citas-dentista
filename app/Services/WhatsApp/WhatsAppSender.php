<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppMessage;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class WhatsAppSender
{
    /**
     * @return array{provider:string,message_id:?string,payload:array,raw:array}
     *
     * @throws RequestException
     */
    public function send(WhatsAppMessage $message): array
    {
        return match (config('whatsapp.driver')) {
            'twilio' => $this->sendViaTwilio($message),
            'cloud_api' => $this->sendViaCloudApi($message),
            'log' => $this->sendViaLog($message),
            default => throw new RuntimeException('Unsupported WhatsApp driver: '.config('whatsapp.driver')),
        };
    }

    /**
     * Send a one-off test message without persisting a database record.
     *
     * @return array{provider:string,message_id:?string,payload:array,raw:array}
     */
    public function sendTestMessage(string $recipient, string $body, ?string $mode = null): array
    {
        return match (config('whatsapp.driver')) {
            'twilio' => $this->sendTestViaTwilio($recipient, $body, $mode),
            'cloud_api' => $this->sendTestViaCloudApi($recipient, $body),
            'log' => $this->sendTestViaLog($recipient, $body),
            default => throw new RuntimeException('Unsupported WhatsApp driver: '.config('whatsapp.driver')),
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
        $config = config('whatsapp.twilio');
        $accountSid = $config['account_sid'] ?? null;
        $authToken = $config['auth_token'] ?? null;
        $from = $config['from'] ?? null;
        $messagingServiceSid = $config['messaging_service_sid'] ?? null;

        if (! $accountSid || ! $authToken) {
            throw new RuntimeException('Twilio credentials are not configured.');
        }

        if (! $from && ! $messagingServiceSid) {
            throw new RuntimeException('Twilio WhatsApp sender is not configured.');
        }

        $payload = [
            'from' => $from ? $this->normalizeWhatsAppAddress($from) : null,
            'messaging_service_sid' => $messagingServiceSid,
            'to' => $this->normalizeWhatsAppRecipient($message->telefono),
            'body' => $message->message,
        ];

        $requestPayload = array_filter([
            'From' => $payload['from'],
            'MessagingServiceSid' => $payload['messaging_service_sid'],
            'To' => $payload['to'],
            'Body' => $payload['body'],
        ], static fn ($value) => $value !== null && $value !== '');

        $response = Http::baseUrl('https://api.twilio.com')
            ->acceptJson()
            ->asForm()
            ->withBasicAuth($accountSid, $authToken)
            ->timeout((int) ($config['timeout'] ?? 15))
            ->connectTimeout(10)
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
     * @return array{provider:string,message_id:?string,payload:array,raw:array}
     */
    private function sendTestViaTwilio(string $recipient, string $body, ?string $mode = null): array
    {
        $config = config('whatsapp.twilio');
        $accountSid = $config['account_sid'] ?? null;
        $authToken = $config['auth_token'] ?? null;
        $from = $config['from'] ?? null;
        $messagingServiceSid = $config['messaging_service_sid'] ?? null;
        $mode ??= 'sandbox';

        if (! $accountSid || ! $authToken) {
            throw new RuntimeException('Twilio credentials are not configured.');
        }

        if ($mode === 'service' && ! $messagingServiceSid) {
            throw new RuntimeException('Twilio Messaging Service SID is not configured.');
        }

        if (in_array($mode, ['sandbox', 'sender'], true) && ! $from) {
            throw new RuntimeException('Twilio WhatsApp sender is not configured.');
        }

        $payload = [
            'mode' => $mode,
            'from' => $mode === 'service' ? null : ($from ? $this->normalizeWhatsAppAddress($from) : null),
            'messaging_service_sid' => $mode === 'service' ? $messagingServiceSid : null,
            'to' => $this->normalizeWhatsAppRecipient($recipient),
            'body' => $body,
        ];

        $requestPayload = array_filter([
            'From' => $payload['from'],
            'MessagingServiceSid' => $payload['messaging_service_sid'],
            'To' => $payload['to'],
            'Body' => $payload['body'],
        ], static fn ($value) => $value !== null && $value !== '');

        $response = Http::baseUrl('https://api.twilio.com')
            ->acceptJson()
            ->asForm()
            ->withBasicAuth($accountSid, $authToken)
            ->timeout((int) ($config['timeout'] ?? 15))
            ->connectTimeout(10)
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
     * @return array{provider:string,message_id:?string,payload:array,raw:array}
     *
     * @throws RequestException
     */
    private function sendViaCloudApi(WhatsAppMessage $message): array
    {
        $config = config('whatsapp.cloud_api');
        $phoneNumberId = $config['phone_number_id'] ?? null;
        $accessToken = $config['access_token'] ?? null;

        if (! $phoneNumberId || ! $accessToken) {
            throw new RuntimeException('WhatsApp Cloud API credentials are not configured.');
        }

        $payload = $this->buildTextPayload($message);

        $response = Http::baseUrl(rtrim((string) ($config['base_url'] ?? 'https://graph.facebook.com'), '/'))
            ->acceptJson()
            ->asJson()
            ->withToken($accessToken)
            ->timeout((int) ($config['timeout'] ?? 15))
            ->connectTimeout(10)
            ->post(sprintf('/%s/%s/messages', $config['version'] ?? 'v22.0', $phoneNumberId), $payload)
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
        $config = config('whatsapp.cloud_api');
        $phoneNumberId = $config['phone_number_id'] ?? null;
        $accessToken = $config['access_token'] ?? null;

        if (! $phoneNumberId || ! $accessToken) {
            throw new RuntimeException('WhatsApp Cloud API credentials are not configured.');
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $this->normalizePhoneNumber($recipient),
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $body,
            ],
        ];

        $response = Http::baseUrl(rtrim((string) ($config['base_url'] ?? 'https://graph.facebook.com'), '/'))
            ->acceptJson()
            ->asJson()
            ->withToken($accessToken)
            ->timeout((int) ($config['timeout'] ?? 15))
            ->connectTimeout(10)
            ->post(sprintf('/%s/%s/messages', $config['version'] ?? 'v22.0', $phoneNumberId), $payload)
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

    private function normalizeWhatsAppAddress(string $address): string
    {
        return str_starts_with($address, 'whatsapp:') ? $address : 'whatsapp:'.ltrim($address);
    }

    private function normalizePhoneNumber(string $recipient): string
    {
        $digits = preg_replace('/\D+/', '', $recipient) ?? '';

        if ($digits === '') {
            return $recipient;
        }

        if (str_starts_with(trim($recipient), '+')) {
            return '+'.$digits;
        }

        $countryCode = preg_replace('/\D+/', '', (string) config('whatsapp.default_country_code', '+34')) ?? '34';

        return '+'.$countryCode.$digits;
    }

    private function normalizeWhatsAppRecipient(string $recipient): string
    {
        $normalized = $this->normalizePhoneNumber($recipient);

        return $normalized !== '' ? 'whatsapp:'.$normalized : '';
    }

    public function twilioTestRecipient(): ?string
    {
        return filled(config('whatsapp.twilio.test_recipient'))
            ? (string) config('whatsapp.twilio.test_recipient')
            : null;
    }
}
