<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppMessage;
use App\Services\WhatsApp\AppointmentDeliveryStatusSyncer;
use App\Services\WhatsApp\WhatsAppResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Twilio\Security\RequestValidator;

class TwilioWhatsAppStatusController extends Controller
{
    public function __invoke(Request $request, AppointmentDeliveryStatusSyncer $deliveryStatusSyncer): Response
    {
        if (! $this->isValidTwilioRequest($request)) {
            Log::warning('Rejected invalid Twilio WhatsApp status callback.', [
                'message_sid' => $request->string('MessageSid')->toString(),
                'message_status' => $request->string('MessageStatus')->toString(),
            ]);

            return response()->noContent(403);
        }

        $payload = $request->all();

        if ($this->isInboundMessage($payload)) {
            $this->processInboundMessage($payload);
        } else {
            $deliveryStatusSyncer->syncFromTwilioWebhook($payload);
        }

        return response()->noContent();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function isInboundMessage(array $payload): bool
    {
        $direction = strtolower(trim((string) data_get($payload, 'Direction', '')));
        $status = strtolower(trim((string) data_get($payload, 'Status', data_get($payload, 'MessageStatus', ''))));
        $buttonText = trim((string) data_get($payload, 'ButtonText', ''));
        $buttonPayload = trim((string) data_get($payload, 'ButtonPayload', ''));
        $body = trim((string) data_get($payload, 'Body', ''));

        if (in_array($direction, ['inbound api', 'inbound-api', 'inbound_api'], true) && $status === 'received') {
            return true;
        }

        return $buttonText !== '' || $buttonPayload !== '' || in_array($body, ['Confirmar', 'Reprogramar'], true);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function processInboundMessage(array $payload): void
    {
        $from = trim((string) data_get($payload, 'From', ''));
        $body = trim((string) data_get($payload, 'Body', ''));
        $buttonText = trim((string) data_get($payload, 'ButtonText', ''));
        $buttonPayload = trim((string) data_get($payload, 'ButtonPayload', ''));
        $responseText = $buttonText !== '' ? $buttonText : ($buttonPayload !== '' ? $buttonPayload : $body);
        $messageSid = trim((string) data_get($payload, 'MessageSid', ''));
        $profileName = trim((string) data_get($payload, 'ProfileName', ''));
        $parentSid = trim((string) data_get($payload, 'ParentMessageSid', ''))
            ?: trim((string) data_get($payload, 'OriginalRepliedMessageSid', ''));
        $conversationSid = trim((string) data_get($payload, 'ConversationSid', ''));

        Log::info('WhatsApp inbound message received.', [
            'from' => $from,
            'body' => $body,
            'button_text' => $buttonText ?: null,
            'button_payload' => $buttonPayload ?: null,
            'message_sid' => $messageSid,
            'parent_message_sid' => $parentSid ?: null,
            'conversation_sid' => $conversationSid ?: null,
        ]);

        if ($from === '' || $responseText === '') {
            return;
        }

        $phone = WhatsAppMessage::normalizePhone($from);
        $message = $this->findMatchingMessage($parentSid, $phone);

        if (! $message) {
            Log::info('No matching WhatsApp message found for inbound response.', [
                'phone' => $phone,
                'body' => $body,
                'parent_message_sid' => $parentSid ?: null,
            ]);

            return;
        }

        $message->update([
            'respuesta' => mb_substr($responseText, 0, 50),
            'responded_at' => now(),
            'provider_payload' => array_merge($message->provider_payload ?? [], [
                'inbound' => [
                    'direction' => strtolower(trim((string) data_get($payload, 'Direction', ''))),
                    'status' => strtolower(trim((string) data_get($payload, 'Status', data_get($payload, 'MessageStatus', '')))),
                    'body' => $body,
                    'button_text' => $buttonText !== '' ? $buttonText : null,
                    'button_payload' => $buttonPayload !== '' ? $buttonPayload : null,
                    'response_text' => $responseText,
                    'message_sid' => $messageSid,
                    'parent_message_sid' => $parentSid ?: null,
                    'conversation_sid' => $conversationSid ?: null,
                    'profile_name' => $profileName,
                    'received_at' => now()->toDateTimeString(),
                    'payload' => $payload,
                ],
            ]),
        ]);

        WhatsAppResponseHandler::process($message);

        Log::info('WhatsApp response recorded.', [
            'message_id' => $message->id,
            'matched_by' => $parentSid !== '' ? 'parent_message_sid' : 'phone_latest',
            'parent_message_sid' => $parentSid ?: null,
            'phone' => $phone,
            'respuesta' => $responseText,
            'appointment_id' => $message->appointment_id,
        ]);
    }

    private function findMatchingMessage(string $parentSid, string $phone): ?WhatsAppMessage
    {
        if ($parentSid !== '') {
            $message = WhatsAppMessage::query()
                ->where('provider_message_id', $parentSid)
                ->where('status', WhatsAppMessage::STATUS_SENT)
                ->first();

            if ($message) {
                return $message;
            }
        }

        return WhatsAppMessage::query()
            ->where('telefono', $phone)
            ->where('status', WhatsAppMessage::STATUS_SENT)
            ->latest('sent_at')
            ->first();
    }

    private function isValidTwilioRequest(Request $request): bool
    {
        $authToken = (string) config('whatsapp.twilio.auth_token', '');
        $signature = (string) $request->header('X-Twilio-Signature', '');
        $callbackUrl = WhatsAppCredential::get()->resolveStatusCallbackUrl();

        if ($authToken === '' || $signature === '') {
            return true;
        }

        if ($signature === '') {
            return false;
        }

        $validator = new RequestValidator($authToken);

        $url = $callbackUrl !== '' ? $callbackUrl : $request->fullUrl();

        return $validator->validate($signature, $url, $request->all());
    }
}
