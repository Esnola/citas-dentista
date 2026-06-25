<?php

namespace App\Services\WhatsApp;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\WhatsAppMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

class AppointmentImmediateSender
{
    /**
     * @return array{sent: bool, message: string}
     */
    public function send(
        Appointment $appointment,
        Client $client,
        WhatsAppSender $sender,
        string $successMessage,
        string $failureMessage,
    ): array {
        $scheduledFor = $appointment->scheduledFor();
        $message = WhatsAppMessage::query()->create([
            'user_id' => Auth::id(),
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => $client->nombre,
            'apellidos' => $client->apellidos,
            'telefono' => $client->telefono,
            'scheduled_for' => $scheduledFor,
            'message' => WhatsAppMessage::buildMessage([
                'nombre' => $client->nombre,
                'apellidos' => $client->apellidos,
                'telefono' => $client->telefono,
                'scheduled_for' => $scheduledFor,
            ]),
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_PENDING,
            'metadata' => [
                'origin_appointment_id' => $appointment->id,
                'immediate_send' => true,
                'immediate_sent_at' => now()->toDateTimeString(),
            ],
        ]);

        try {
            $result = $sender->send($message);
            $providerStatus = (string) data_get($result, 'raw.status', '');
            $providerErrorCode = data_get($result, 'raw.error_code');
            $providerErrorMessage = data_get($result, 'raw.error_message');

            $message->update([
                'status' => $this->whatsAppMessageStatus($providerStatus),
                'sent_at' => $this->isSuccessfulWhatsAppStatus($providerStatus) ? now() : null,
                'last_error' => null,
                'provider_message_id' => $result['message_id'],
                'provider_payload' => [
                    'provider' => $result['provider'],
                    'payload' => $result['payload'],
                    'raw' => $result['raw'],
                ],
            ]);

            if ($this->isCompletedWhatsAppStatus($providerStatus) || $this->isAcceptedWhatsAppStatus($providerStatus)) {
                $appointment->update([
                    'enviado' => true,
                ]);

                return [
                    'sent' => true,
                    'message' => $successMessage,
                ];
            }

            if ($this->isFailedWhatsAppStatus($providerStatus)) {
                $errorDetail = collect([
                    $providerStatus !== '' ? 'estado: '.$providerStatus : null,
                    $providerErrorCode ? 'código: '.$providerErrorCode : null,
                    $providerErrorMessage ? 'mensaje: '.$providerErrorMessage : null,
                ])->filter()->implode(', ');

                $message->update([
                    'last_error' => $errorDetail !== '' ? $errorDetail : 'El proveedor no completó el envío.',
                ]);

                return [
                    'sent' => false,
                    'message' => $failureMessage.' '.($errorDetail !== '' ? $errorDetail.'. ' : '').'La cita no se ha marcado como enviada.',
                ];
            }

            $pendingStatus = $providerStatus !== '' ? $providerStatus : 'pendiente';

            return [
                'sent' => false,
                'message' => 'WhatsApp enviado al proveedor, pero no se pudo confirmar el resultado (estado: '.$pendingStatus.'). La cita no se ha marcado como enviada.',
            ];
        } catch (Throwable $throwable) {
            $message->update([
                'status' => WhatsAppMessage::STATUS_FAILED,
                'last_error' => $throwable->getMessage(),
            ]);

            return [
                'sent' => false,
                'message' => $failureMessage.' Error: '.Str::limit($throwable->getMessage(), 220).'. La cita no se ha marcado como enviada.',
            ];
        }
    }

    private function isCompletedWhatsAppStatus(string $providerStatus): bool
    {
        return in_array($providerStatus, ['sent', 'delivered'], true);
    }

    private function isAcceptedWhatsAppStatus(string $providerStatus): bool
    {
        return in_array($providerStatus, ['accepted', 'queued', 'sending'], true);
    }

    private function isSuccessfulWhatsAppStatus(string $providerStatus): bool
    {
        return $this->isCompletedWhatsAppStatus($providerStatus)
            || $this->isAcceptedWhatsAppStatus($providerStatus);
    }

    private function isFailedWhatsAppStatus(string $providerStatus): bool
    {
        return in_array($providerStatus, ['failed', 'undelivered'], true);
    }

    private function whatsAppMessageStatus(string $providerStatus): string
    {
        if ($this->isCompletedWhatsAppStatus($providerStatus)) {
            return WhatsAppMessage::STATUS_SENT;
        }

        if ($this->isFailedWhatsAppStatus($providerStatus)) {
            return WhatsAppMessage::STATUS_FAILED;
        }

        if ($this->isAcceptedWhatsAppStatus($providerStatus)) {
            return WhatsAppMessage::STATUS_SENT;
        }

        return WhatsAppMessage::STATUS_PENDING;
    }
}
