<?php

namespace App\Services\WhatsApp;

use App\Models\Appointment;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AppointmentDeliveryStatusSyncer
{
    public function syncAll(?int $clientId = null): int
    {
        if (! $this->canSync()) {
            return 0;
        }

        $messages = WhatsAppMessage::query()
            ->whereNotNull('appointment_id')
            ->when($clientId, fn ($query) => $query->where('client_id', $clientId))
            ->get(['id', 'appointment_id', 'provider_message_id', 'sent_at', 'created_at', 'provider_payload']);

        return $this->syncAppointmentsFromMessages($this->refreshMessages($messages));
    }

    public function backfillFromStoredMessages(?int $clientId = null): int
    {
        if (! $this->canSync()) {
            return 0;
        }

        $messages = WhatsAppMessage::query()
            ->whereNotNull('appointment_id')
            ->when($clientId, fn ($query) => $query->where('client_id', $clientId))
            ->get(['id', 'appointment_id', 'sent_at', 'created_at', 'provider_payload']);

        return $this->syncAppointmentsFromMessages($messages);
    }

    /**
     * @param  iterable<int>|Collection<int, int>  $appointmentIds
     */
    public function sync(iterable $appointmentIds): int
    {
        if (! $this->canSync()) {
            return 0;
        }

        $ids = collect($appointmentIds)
            ->filter(fn (mixed $appointmentId): bool => (int) $appointmentId > 0)
            ->map(fn (mixed $appointmentId): int => (int) $appointmentId)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return 0;
        }

        $messages = WhatsAppMessage::query()
            ->whereIn('appointment_id', $ids)
            ->whereNotNull('appointment_id')
            ->get(['id', 'appointment_id', 'provider_message_id', 'sent_at', 'created_at', 'provider_payload']);

        return $this->syncAppointmentsFromMessages($this->refreshMessages($messages));
    }

    /**
     * Persist a Twilio delivery callback and sync the related appointment state.
     *
     * @param  array<string, mixed>  $payload
     */
    public function syncFromTwilioWebhook(array $payload): int
    {
        if (! $this->canSync()) {
            return 0;
        }

        $messageSid = trim((string) data_get($payload, 'MessageSid', ''));

        if ($messageSid === '') {
            return 0;
        }

        $message = WhatsAppMessage::query()
            ->where('provider_message_id', $messageSid)
            ->first();

        if (! $message || ! $message->appointment_id) {
            return 0;
        }

        $providerPayload = $message->provider_payload ?? [];
        $providerPayload['callback'] = [
            'message_status' => strtolower(trim((string) data_get($payload, 'MessageStatus', ''))),
            'event_type' => strtoupper(trim((string) data_get($payload, 'EventType', ''))),
            'received_at' => now()->toDateTimeString(),
            'payload' => $payload,
        ];

        $message->update([
            'provider_payload' => $providerPayload,
        ]);

        return $this->sync([$message->appointment_id]);
    }

    /**
     * @param  Collection<int, WhatsAppMessage>  $messages
     * @return Collection<int, WhatsAppMessage>
     */
    private function refreshMessages(Collection $messages): Collection
    {
        if ($messages->isEmpty()) {
            return $messages;
        }

        return $messages->map(function (WhatsAppMessage $message): WhatsAppMessage {
            if ($this->messageWasDelivered($message)) {
                return $message;
            }

            return $this->refreshMessageFromTwilio($message);
        });
    }

    private function refreshMessageFromTwilio(WhatsAppMessage $message): WhatsAppMessage
    {
        if (! $this->shouldPollTwilio($message)) {
            return $message;
        }

        $accountSid = trim((string) config('whatsapp.twilio.account_sid', ''));
        $authToken = trim((string) config('whatsapp.twilio.auth_token', ''));
        $providerMessageId = trim((string) $message->provider_message_id);

        if ($accountSid === '' || $authToken === '' || $providerMessageId === '') {
            return $message;
        }

        try {
            $response = Http::baseUrl('https://api.twilio.com')
                ->acceptJson()
                ->withBasicAuth($accountSid, $authToken)
                ->retry([100, 500, 1000])
                ->timeout((int) config('whatsapp.twilio.timeout', 15))
                ->connectTimeout((int) config('whatsapp.twilio.connect_timeout', 10))
                ->get('/2010-04-01/Accounts/'.$accountSid.'/Messages/'.$providerMessageId.'.json')
                ->throw()
                ->json();
        } catch (Throwable) {
            return $message;
        }

        if (! is_array($response) || $response === []) {
            return $message;
        }

        $providerPayload = $message->provider_payload ?? [];
        $providerPayload['provider'] = 'twilio';
        $providerPayload['raw'] = $response;
        $providerPayload['sync'] = [
            'source' => 'twilio_api',
            'received_at' => now()->toDateTimeString(),
        ];

        $message->forceFill([
            'provider_payload' => $providerPayload,
        ]);
        $message->save();

        return $message;
    }

    private function shouldPollTwilio(WhatsAppMessage $message): bool
    {
        if ((string) data_get($message->provider_payload, 'provider') !== 'twilio') {
            return false;
        }

        if (! filled($message->provider_message_id)) {
            return false;
        }

        if ($this->messageWasDelivered($message)) {
            return false;
        }

        $messageAge = $this->messageAge($message);

        return $messageAge === null || $messageAge->greaterThanOrEqualTo(now()->subDay());
    }

    private function canSync(): bool
    {
        return Schema::hasColumn('appointments', 'entregado');
    }

    /**
     * @param  Collection<int, WhatsAppMessage>  $messages
     */
    private function syncAppointmentsFromMessages(Collection $messages): int
    {
        $groupedMessages = $messages->groupBy('appointment_id');

        if ($groupedMessages->isEmpty()) {
            return 0;
        }

        $updated = 0;

        foreach ($groupedMessages as $appointmentId => $appointmentMessages) {
            $appointment = Appointment::query()->find($appointmentId);

            if (! $appointment) {
                continue;
            }

            $sentAt = $this->latestTimestamp($appointmentMessages->map(fn (WhatsAppMessage $message): ?Carbon => $message->sent_at));
            $deliveredAt = $this->latestTimestamp($appointmentMessages->map(fn (WhatsAppMessage $message): ?Carbon => $message->deliveredAt()));
            $readAt = $this->latestTimestamp($appointmentMessages->map(fn (WhatsAppMessage $message): ?Carbon => $message->readAt()));

            $appointment->forceFill([
                'enviado' => $appointment->enviado || $sentAt !== null,
                'whatsapp_sent_at' => $this->latestTimestamp(collect([$appointment->whatsapp_sent_at, $sentAt])),
                'entregado' => $appointment->entregado || $deliveredAt !== null,
                'whatsapp_delivered_at' => $this->latestTimestamp(collect([$appointment->whatsapp_delivered_at, $deliveredAt])),
                'whatsapp_read_at' => $this->latestTimestamp(collect([$appointment->whatsapp_read_at, $readAt])),
            ]);

            if ($appointment->isDirty()) {
                $appointment->save();
                $updated++;
            }
        }

        return $updated;
    }

    private function messageWasDelivered(WhatsAppMessage $message): bool
    {
        $callbackStatus = strtolower(trim((string) data_get($message->provider_payload, 'callback.message_status', '')));
        $callbackEventType = strtoupper(trim((string) data_get($message->provider_payload, 'callback.event_type', '')));
        $rawStatus = strtolower(trim((string) data_get($message->provider_payload, 'raw.status', '')));

        if (in_array($callbackStatus, ['delivered', 'read'], true) || $callbackEventType === 'READ') {
            return true;
        }

        return in_array($rawStatus, ['delivered', 'read'], true);
    }

    private function messageAge(WhatsAppMessage $message): ?Carbon
    {
        $timestamp = $message->sent_at ?? $message->created_at;

        return $timestamp instanceof Carbon ? $timestamp : null;
    }

    /**
     * @param  Collection<int, Carbon|null>  $timestamps
     */
    private function latestTimestamp(Collection $timestamps): ?Carbon
    {
        return $timestamps
            ->filter(fn (?Carbon $timestamp): bool => $timestamp instanceof Carbon)
            ->sortBy(fn (Carbon $timestamp): int => $timestamp->getTimestamp())
            ->last();
    }
}
