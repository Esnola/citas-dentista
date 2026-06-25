<?php

namespace App\Services\WhatsApp;

use App\Models\Appointment;
use App\Models\WhatsAppMessage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class AppointmentDeliveryStatusSyncer
{
    public function syncAll(?int $clientId = null): void
    {
        if (! $this->canSync()) {
            return;
        }

        $messages = WhatsAppMessage::query()
            ->whereNotNull('appointment_id')
            ->when($clientId, fn ($query) => $query->where('client_id', $clientId))
            ->get(['appointment_id', 'provider_payload']);

        $this->markDeliveredAppointments($messages);
    }

    /**
     * @param  iterable<int>|Collection<int, int>  $appointmentIds
     */
    public function sync(iterable $appointmentIds): void
    {
        if (! $this->canSync()) {
            return;
        }

        $ids = collect($appointmentIds)
            ->filter(fn (mixed $appointmentId): bool => (int) $appointmentId > 0)
            ->map(fn (mixed $appointmentId): int => (int) $appointmentId)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return;
        }

        $messages = WhatsAppMessage::query()
            ->whereIn('appointment_id', $ids)
            ->whereNotNull('appointment_id')
            ->get(['appointment_id', 'provider_payload']);

        $this->markDeliveredAppointments($messages);
    }

    private function canSync(): bool
    {
        return Schema::hasColumn('appointments', 'entregado');
    }

    /**
     * @param  Collection<int, WhatsAppMessage>  $messages
     */
    private function markDeliveredAppointments(Collection $messages): void
    {
        $deliveredAppointmentIds = $messages
            ->filter(fn (WhatsAppMessage $message): bool => data_get($message->provider_payload, 'raw.status') === 'delivered')
            ->pluck('appointment_id')
            ->unique()
            ->values();

        if ($deliveredAppointmentIds->isEmpty()) {
            return;
        }

        Appointment::query()
            ->whereIn('id', $deliveredAppointmentIds)
            ->where('entregado', false)
            ->update(['entregado' => true]);
    }
}
