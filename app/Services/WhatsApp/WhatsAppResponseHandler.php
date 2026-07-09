<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class WhatsAppResponseHandler
{
    /**
     * Create an inbound response record and update appointment flags.
     */
    public static function process(WhatsAppMessage $outbound, string $responseText, array $inboundPayload = []): WhatsAppMessage
    {
        $messageSid = trim((string) data_get($inboundPayload, 'message_sid', ''));
        $receivedAt = data_get($inboundPayload, 'received_at', '');
        $twilioTime = $receivedAt !== '' ? Carbon::parse($receivedAt)->timezone(config('app.timezone')) : now();

        $inbound = WhatsAppMessage::query()->create([
            'user_id' => $outbound->user_id,
            'client_id' => $outbound->client_id,
            'appointment_id' => $outbound->appointment_id,
            'parent_id' => $outbound->id,
            'nombre' => $outbound->nombre,
            'apellidos' => $outbound->apellidos,
            'telefono' => $outbound->telefono,
            'scheduled_for' => $twilioTime,
            'message' => $responseText,
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_SENT,
            'direction' => WhatsAppMessage::DIRECTION_INBOUND,
            'sent_at' => $twilioTime,
            'provider_message_id' => $messageSid !== '' ? $messageSid : null,
            'respuesta' => mb_substr($responseText, 0, 50),
            'responded_at' => $twilioTime,
            'provider_payload' => $inboundPayload !== [] ? ['inbound' => $inboundPayload] : null,
        ]);

        self::updateAppointmentFlags($inbound);

        return $inbound;
    }

    /**
     * Update appointment confirmada/pendiente_reprogramacion based on the inbound message.
     */
    public static function updateAppointmentFlags(WhatsAppMessage $inbound): void
    {
        $appointment = $inbound->appointment;

        if (! $appointment) {
            return;
        }

        $buttonPayload = strtolower(trim((string) data_get($inbound->provider_payload, 'inbound.button_payload', '')));
        $respuesta = strtolower(trim((string) $inbound->respuesta));

        $isConfirmed = $buttonPayload !== ''
            ? str_starts_with($buttonPayload, 'confirm')
            : in_array($respuesta, ['confirmar', 'confirmar cita'], true);

        if ($isConfirmed) {
            $appointment->update([
                'confirmada' => true,
                'pendiente_reprogramacion' => false,
            ]);

            return;
        }

        $isReschedule = $buttonPayload !== ''
            ? str_starts_with($buttonPayload, 'reprogram') || str_starts_with($buttonPayload, 'cambiar')
            : in_array($respuesta, ['reprogramar', 'reprogramar cita', 'cambiar', 'cambiar cita'], true);

        if ($isReschedule) {
            $appointment->update([
                'pendiente_reprogramacion' => true,
                'confirmada' => false,
            ]);

            return;
        }

        Log::info('WhatsApp response received (no action).', [
            'message_id' => $inbound->id,
            'appointment_id' => $appointment->id,
            'respuesta' => $inbound->respuesta,
            'button_payload' => $buttonPayload ?: null,
        ]);
    }
}
