<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppMessage;
use Livewire\Component;

class UnreadResponsesNotice extends Component
{
    public function render()
    {
        $appointments = Appointment::query()
            ->whereHas('whatsAppMessages', fn ($query) => $query
                ->where('direction', WhatsAppMessage::DIRECTION_INBOUND)
                ->whereNotNull('respuesta'))
            ->with([
                'client',
                'whatsAppMessages' => fn ($query) => $query
                    ->select([
                        'id',
                        'appointment_id',
                        'direction',
                        'message',
                        'respuesta',
                        'responded_at',
                        'sent_at',
                        'created_at',
                        'provider_payload',
                    ])
                    ->orderByDesc('created_at'),
            ])
            ->get()
            ->filter->hasUnreadInboundResponse()
            ->sortByDesc(function (Appointment $appointment): int {
                $latestInbound = $appointment->latestInboundAfterLastSent();

                return $latestInbound?->responded_at?->timestamp
                    ?? $latestInbound?->created_at?->timestamp
                    ?? 0;
            })
            ->take(5)
            ->values()
            ->map(function (Appointment $appointment): array {
                $latestInbound = $appointment->latestInboundAfterLastSent();

                return [
                    'appointment_id' => $appointment->id,
                    'client_name' => $appointment->client?->full_name ?? 'Cliente',
                    'response' => $latestInbound?->responseValue() ?? '',
                    'responded_at' => $latestInbound?->responded_at ?? $latestInbound?->created_at,
                    'url' => route('clients.appointments', [
                        'client' => $appointment->client_id,
                        'history' => $appointment->id,
                    ]),
                ];
            });

        return view('livewire.unread-responses-notice', [
            'items' => $appointments,
            'pollInterval' => WhatsAppCredential::webhookEnabled() ? 2 : WhatsAppCredential::pollInterval(),
        ]);
    }
}
