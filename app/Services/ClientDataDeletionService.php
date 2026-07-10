<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\WhatsAppMessage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ClientDataDeletionService
{
    /**
     * @param  iterable<int, int|string>  $appointmentIds
     */
    public function deleteAppointments(iterable $appointmentIds, ?int $clientId = null): int
    {
        $appointmentIds = $this->appointmentIds($appointmentIds, $clientId);

        if ($appointmentIds->isEmpty()) {
            return 0;
        }

        return DB::transaction(function () use ($appointmentIds): int {
            $this->deleteWhatsAppMessagesForAppointments($appointmentIds);

            return Appointment::query()
                ->whereKey($appointmentIds)
                ->delete();
        });
    }

    public function deleteClientById(int $clientId): bool
    {
        return DB::transaction(function () use ($clientId): bool {
            $client = Client::query()->find($clientId);

            if (! $client) {
                return false;
            }

            $appointmentIds = $client->appointments()->pluck('id');

            $messageIds = WhatsAppMessage::query()
                ->where('client_id', $client->id)
                ->when($appointmentIds->isNotEmpty(), fn ($query) => $query->orWhereIn('appointment_id', $appointmentIds))
                ->pluck('id');

            WhatsAppMessage::query()
                ->whereIn('id', $messageIds)
                ->orWhereIn('parent_id', $messageIds)
                ->delete();

            return (bool) $client->delete();
        });
    }

    /**
     * @param  iterable<int, int|string>  $appointmentIds
     * @return Collection<int, int>
     */
    private function appointmentIds(iterable $appointmentIds, ?int $clientId): Collection
    {
        $ids = collect($appointmentIds)
            ->map(fn (int|string $id): int => (int) $id)
            ->filter()
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return collect();
        }

        return Appointment::query()
            ->when($clientId, fn ($query) => $query->where('client_id', $clientId))
            ->whereKey($ids)
            ->pluck('id');
    }

    /**
     * @param  Collection<int, int>  $appointmentIds
     */
    private function deleteWhatsAppMessagesForAppointments(Collection $appointmentIds): void
    {
        $messageIds = WhatsAppMessage::query()
            ->whereIn('appointment_id', $appointmentIds)
            ->pluck('id');

        WhatsAppMessage::query()
            ->whereIn('appointment_id', $appointmentIds)
            ->when($messageIds->isNotEmpty(), fn ($query) => $query->orWhereIn('parent_id', $messageIds))
            ->delete();
    }
}
