<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppMessage;
use Illuminate\Support\Facades\Log;

class WhatsAppResponseHandler
{
    public static function process(WhatsAppMessage $message): void
    {
        $appointment = $message->appointment;

        if (! $appointment) {
            return;
        }

        $buttonPayload = strtolower(trim((string) data_get($message->provider_payload, 'inbound.button_payload', '')));

        $isConfirmed = $buttonPayload !== ''
            ? str_starts_with($buttonPayload, 'confirm')
            : strtolower(trim((string) $message->respuesta)) === 'confirmar';

        if ($isConfirmed) {
            $appointment->update([
                'confirmada' => true,
                'pendiente_reprogramacion' => false,
            ]);

            return;
        }

        Log::info('WhatsApp response received (no action).', [
            'message_id' => $message->id,
            'appointment_id' => $appointment->id,
            'respuesta' => $message->respuesta,
            'button_payload' => $buttonPayload ?: null,
        ]);
    }
}
