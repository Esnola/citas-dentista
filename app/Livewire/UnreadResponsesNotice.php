<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppMessage;
use App\Services\WhatsApp\AppointmentDeliveryStatusSyncer;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class UnreadResponsesNotice extends Component
{
    private AppointmentDeliveryStatusSyncer $deliveryStatusSyncer;

    public function boot(AppointmentDeliveryStatusSyncer $deliveryStatusSyncer): void
    {
        $this->deliveryStatusSyncer = $deliveryStatusSyncer;
    }

    public function pollUpdates(): void
    {
        if (! $this->shouldSyncFromTwilio()) {
            return;
        }

        $this->deliveryStatusSyncer->syncAll(force: true);
    }

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
                    'response_badge' => $this->responseBadge($latestInbound),
                    'responded_at' => $latestInbound?->responded_at ?? $latestInbound?->created_at,
                    'client_url' => route('clients.appointments', $appointment->client_id),
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

    /** @return array{label: string, classes: string, icono: string}|null */
    private function responseBadge(?WhatsAppMessage $message): ?array
    {
        if (! $message) {
            return null;
        }

        if ($message->isConfirmed()) {
            return [
                'label' => 'Cita Confirmada',
                'classes' => 'bg-emerald-500/15 text-emerald-300 border border-emerald-400/30',
                'icono' => 'usuario-plus',
            ];
        }

        if ($message->isRescheduleRequested()) {
            return [
                'label' => 'Hay Incidencia',
                'classes' => 'bg-red-500/15 text-red-300 border border-red-400/30',
                'icono' => 'alert',
            ];
        }

        $label = $message->responseValue();

        if (! $label) {
            return null;
        }

        return [
            'label' => $label,
            'classes' => 'bg-sky-500/20 text-sky-200 border border-sky-400/40 ring-2 ring-sky-300/20',
            'icono' => 'ojo',
        ];
    }

    private function shouldSyncFromTwilio(): bool
    {
        $cacheKey = 'unread_responses_notice_twilio_synced_at';
        $lastSyncedAt = (int) Cache::get($cacheKey, 0);
        $now = now()->timestamp;

        if ($lastSyncedAt > 0 && $lastSyncedAt <= $now && ($now - $lastSyncedAt) < $this->twilioSyncInterval()) {
            return false;
        }

        Cache::put($cacheKey, $now, now()->addMinutes(5));

        return true;
    }

    private function twilioSyncInterval(): int
    {
        $credential = WhatsAppCredential::get();
        $interval = (int) ($credential->poll_interval ?? 10);

        return max(5, min(60, $interval));
    }
}
