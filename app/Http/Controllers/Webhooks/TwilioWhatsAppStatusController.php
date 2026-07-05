<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
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
        $buttonText = trim((string) data_get($payload, 'ButtonText', ''));
        $body = trim((string) data_get($payload, 'Body', ''));

        return $buttonText !== '' || in_array($body, ['Confirmar', 'Reprogramar'], true);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function processInboundMessage(array $payload): void
    {
        $from = trim((string) data_get($payload, 'From', ''));
        $body = trim((string) data_get($payload, 'Body', ''));
        $messageSid = trim((string) data_get($payload, 'MessageSid', ''));
        $profileName = trim((string) data_get($payload, 'ProfileName', ''));

        Log::info('WhatsApp inbound button response received.', [
            'from' => $from,
            'body' => $body,
            'message_sid' => $messageSid,
        ]);

        if ($from === '' || $body === '') {
            return;
        }

        $phone = WhatsAppMessage::normalizePhone($from);

        $message = WhatsAppMessage::query()
            ->where('telefono', $phone)
            ->whereNull('respuesta')
            ->where('status', WhatsAppMessage::STATUS_SENT)
            ->latest('sent_at')
            ->first();

        if (! $message) {
            Log::info('No matching WhatsApp message found for inbound response.', [
                'phone' => $phone,
                'body' => $body,
            ]);

            return;
        }

        $message->update([
            'respuesta' => $body,
            'responded_at' => now(),
            'provider_payload' => array_merge($message->provider_payload ?? [], [
                'inbound' => [
                    'message_sid' => $messageSid,
                    'profile_name' => $profileName,
                    'received_at' => now()->toDateTimeString(),
                    'payload' => $payload,
                ],
            ]),
        ]);

        WhatsAppResponseHandler::process($message);

        Log::info('WhatsApp response recorded.', [
            'message_id' => $message->id,
            'phone' => $phone,
            'respuesta' => $body,
            'appointment_id' => $message->appointment_id,
        ]);
    }

    private function isValidTwilioRequest(Request $request): bool
    {
        $authToken = (string) config('whatsapp.twilio.auth_token', '');
        $signature = (string) $request->header('X-Twilio-Signature', '');
        $callbackUrl = trim((string) config('whatsapp.twilio.status_callback_url', ''));

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
