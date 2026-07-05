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

        match ($message->respuesta) {
            WhatsAppMessage::RESPUESTA_CONFIRMAR => $appointment->confirmar(),
            WhatsAppMessage::RESPUESTA_REPROGRAMAR => $appointment->marcarReprogramacion(),
            default => Log::info('WhatsApp response received (no action).', [
                'message_id' => $message->id,
                'appointment_id' => $appointment->id,
                'respuesta' => $message->respuesta,
            ]),
        };
    }
}
