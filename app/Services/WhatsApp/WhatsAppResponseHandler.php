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
            WhatsAppMessage::RESPUESTA_CONFIRMAR => $appointment->update([
                'confirmada' => true,
                'pendiente_reprogramacion' => false,
            ]),
            WhatsAppMessage::RESPUESTA_REPROGRAMAR => $appointment->update([
                'confirmada' => false,
                'pendiente_reprogramacion' => true,
            ]),
            default => Log::info('WhatsApp response received (no action).', [
                'message_id' => $message->id,
                'appointment_id' => $appointment->id,
                'respuesta' => $message->respuesta,
            ]),
        };
    }
}
