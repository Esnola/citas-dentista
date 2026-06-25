<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Services\WhatsApp\AppointmentDeliveryStatusSyncer;
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

        $deliveryStatusSyncer->syncFromTwilioWebhook($request->all());

        return response()->noContent();
    }

    private function isValidTwilioRequest(Request $request): bool
    {
        $authToken = (string) config('whatsapp.twilio.auth_token', '');
        $signature = (string) $request->header('X-Twilio-Signature', '');
        $callbackUrl = trim((string) config('whatsapp.twilio.status_callback_url', ''));

        if ($authToken === '' || $signature === '') {
            return false;
        }

        $validator = new RequestValidator($authToken);

        $url = $callbackUrl !== '' ? $callbackUrl : $request->fullUrl();

        return $validator->validate($signature, $url, $request->all());
    }
}
