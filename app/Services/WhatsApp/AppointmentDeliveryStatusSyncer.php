<?php

namespace App\Services\WhatsApp;

use App\Models\Appointment;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AppointmentDeliveryStatusSyncer
{
    public function syncAll(?int $clientId = null, bool $force = false): int
    {
        if (! $this->canSync()) {
            return 0;
        }

        $this->syncInboundResponses($clientId);

        $messages = WhatsAppMessage::query()
            ->whereNotNull('appointment_id')
            ->when($clientId, fn ($query) => $query->where('client_id', $clientId))
            ->get(['id', 'appointment_id', 'provider_message_id', 'sent_at', 'created_at', 'provider_payload', 'metadata']);

        return $this->syncAppointmentsFromMessages($this->refreshMessages($messages, $force));
    }

    public function backfillFromStoredMessages(?int $clientId = null): int
    {
        if (! $this->canSync()) {
            return 0;
        }

        $messages = WhatsAppMessage::query()
            ->whereNotNull('appointment_id')
            ->when($clientId, fn ($query) => $query->where('client_id', $clientId))
            ->get(['id', 'appointment_id', 'sent_at', 'created_at', 'provider_payload', 'metadata']);

        return $this->syncAppointmentsFromMessages($messages);
    }

    /**
     * @param  iterable<int>|Collection<int, int>  $appointmentIds
     */
    public function sync(iterable $appointmentIds, bool $force = false): int
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

        $clientIds = Appointment::query()
            ->whereIn('id', $ids)
            ->pluck('client_id')
            ->unique()
            ->values();

        foreach ($clientIds as $clientId) {
            $this->syncInboundResponses($clientId);
        }

        $messages = WhatsAppMessage::query()
            ->whereIn('appointment_id', $ids)
            ->whereNotNull('appointment_id')
            ->get(['id', 'appointment_id', 'provider_message_id', 'sent_at', 'created_at', 'provider_payload', 'metadata']);

        return $this->syncAppointmentsFromMessages($this->refreshMessages($messages, $force));
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
     * Query Twilio API for inbound messages and recover responses the webhook missed.
     */
    public function syncInboundResponses(?int $clientId = null): int
    {
        if (! $this->canSync()) {
            return 0;
        }

        $credential = WhatsAppCredential::get();
        $accountSid = trim((string) ($credential->resolveAccountSid() ?? ''));
        [$username, $password] = $this->twilioApiCredentials($credential);

        if ($accountSid === '' || $username === '' || $password === '') {
            return 0;
        }

        $sentMessages = WhatsAppMessage::query()
            ->where('direction', WhatsAppMessage::DIRECTION_OUTBOUND)
            ->where('status', WhatsAppMessage::STATUS_SENT)
            ->whereNotNull('provider_message_id')
            ->when($clientId, fn ($query) => $query->where('client_id', $clientId))
            ->get();

        if ($sentMessages->isEmpty()) {
            return 0;
        }

        $phoneGroups = $sentMessages->groupBy('telefono');
        $recovered = 0;

        foreach ($phoneGroups as $phone => $phoneMessages) {
            $twilioPhone = $phoneMessages->first()->twilioPhone();

            if ($twilioPhone === '') {
                continue;
            }

            $inboundMessages = $this->fetchInboundFromTwilio(
                $accountSid, $username, $password,
                $credential, $twilioPhone
            );

            if ($inboundMessages->isEmpty()) {
                continue;
            }

            $recovered += $this->matchInboundToOutbound($phoneMessages, $inboundMessages);
        }

        return $recovered;
    }

    private function twilioApiCredentials(WhatsAppCredential $credential): array
    {
        $apiKeySid = trim((string) ($credential->resolveApiKeySid() ?? ''));
        $apiKeySecret = trim((string) ($credential->resolveApiKeySecret() ?? ''));

        if ($apiKeySid !== '' && $apiKeySecret !== '') {
            return [$apiKeySid, $apiKeySecret];
        }

        return [
            $credential->resolveAccountSid(),
            $credential->resolveAuthToken(),
        ];
    }

    /**
     * @return Collection<int, array{sid:string,body:string,from:string,to:string,date_sent:string,direction:string}>
     */
    private function fetchInboundFromTwilio(
        string $accountSid,
        string $username,
        string $password,
        WhatsAppCredential $credential,
        string $twilioPhone,
    ): Collection {
        try {
            $response = Http::baseUrl('https://api.twilio.com')
                ->acceptJson()
                ->withBasicAuth($username, $password)
                ->retry([100, 500, 1000])
                ->timeout($credential->resolveTimeout())
                ->connectTimeout($credential->resolveConnectTimeout())
                ->get('/2010-04-01/Accounts/'.$accountSid.'/Messages.json', [
                    'From' => $twilioPhone,
                    'Direction' => 'inbound',
                    'PageSize' => 50,
                ])
                ->throw()
                ->json();

            $messages = data_get($response, 'messages', []);

            return collect($messages)->filter(fn (array $msg): bool => strtolower(trim((string) data_get($msg, 'direction', ''))) !== 'outbound-api');
        } catch (Throwable $e) {
            Log::warning('Failed to fetch inbound messages from Twilio.', [
                'error' => $e->getMessage(),
                'phone' => $twilioPhone,
            ]);

            return collect();
        }
    }

    /**
     * Match inbound Twilio messages to outgoing WhatsAppMessages without a response.
     *
     * @param  Collection<int, WhatsAppMessage>  $outboundMessages
     * @param  Collection<int, array{sid:string,body:string,from:string,to:string,date_sent:string,direction:string}>  $inboundMessages
     */
    private function matchInboundToOutbound(Collection $outboundMessages, Collection $inboundMessages): int
    {
        $recovered = 0;
        $sorted = $outboundMessages->sortBy('sent_at')->values();

        $existingSids = WhatsAppMessage::query()
            ->where('direction', WhatsAppMessage::DIRECTION_INBOUND)
            ->whereNotNull('provider_message_id')
            ->pluck('provider_message_id')
            ->flip();

        foreach ($inboundMessages as $inbound) {
            $inboundDate = Carbon::parse(data_get($inbound, 'date_sent', ''))->timezone(config('app.timezone'));
            $body = trim((string) data_get($inbound, 'body', ''));
            $inboundSid = trim((string) data_get($inbound, 'sid', ''));

            if ($body === '' || $inboundSid === '') {
                continue;
            }

            if ($existingSids->has($inboundSid)) {
                continue;
            }

            $parentSid = trim((string) data_get($inbound, 'in_reply_to', ''));

            $matched = null;

            if ($parentSid !== '') {
                $matched = $sorted->first(
                    fn (WhatsAppMessage $msg): bool => $msg->provider_message_id === $parentSid
                );
            }

            if (! $matched) {
                $matched = $sorted->filter(
                    fn (WhatsAppMessage $msg): bool => $msg->sent_at !== null
                        && $msg->sent_at->lte($inboundDate)
                        && $inboundDate->diffInSeconds($msg->sent_at) < 86400
                )->sortByDesc(fn (WhatsAppMessage $msg): int => $msg->sent_at->timestamp)->first();
            }

            if (! $matched) {
                continue;
            }

            $inboundPayload = [
                'direction' => strtolower(trim((string) data_get($inbound, 'direction', ''))),
                'status' => 'received',
                'body' => $body,
                'button_text' => null,
                'button_payload' => null,
                'response_text' => $body,
                'message_sid' => $inboundSid,
                'parent_message_sid' => $parentSid ?: null,
                'conversation_sid' => null,
                'profile_name' => '',
                'received_at' => $inboundDate->toDateTimeString(),
                'source' => 'twilio_api_sync',
            ];

            WhatsAppResponseHandler::process($matched, $body, $inboundPayload);

            $existingSids->put($inboundSid, true);

            $recovered++;
        }

        return $recovered;
    }

    /**
     * @param  Collection<int, WhatsAppMessage>  $messages
     * @return Collection<int, WhatsAppMessage>
     */
    private function refreshMessages(Collection $messages, bool $force = false): Collection
    {
        if ($messages->isEmpty()) {
            return $messages;
        }

        return $messages->map(function (WhatsAppMessage $message) use ($force): WhatsAppMessage {
            if ($this->messageWasRead($message)) {
                return $message;
            }

            return $this->refreshMessageFromTwilio($message, $force);
        });
    }

    private function refreshMessageFromTwilio(WhatsAppMessage $message, bool $force = false): WhatsAppMessage
    {
        if (! $this->shouldPollTwilio($message, $force)) {
            return $message;
        }

        $credential = WhatsAppCredential::get();
        $accountSid = trim((string) ($credential->resolveAccountSid() ?? ''));
        $apiKeySid = trim((string) ($credential->resolveApiKeySid() ?? ''));
        $apiKeySecret = trim((string) ($credential->resolveApiKeySecret() ?? ''));
        $username = $apiKeySid !== '' && $apiKeySecret !== '' ? $apiKeySid : $accountSid;
        $password = $apiKeySid !== '' && $apiKeySecret !== ''
            ? $apiKeySecret
            : trim((string) ($credential->resolveAuthToken() ?? ''));
        $providerMessageId = trim((string) $message->provider_message_id);

        if ($accountSid === '' || $username === '' || $password === '' || $providerMessageId === '') {
            return $message;
        }

        try {
            $response = Http::baseUrl('https://api.twilio.com')
                ->acceptJson()
                ->withBasicAuth($username, $password)
                ->retry([100, 500, 1000])
                ->timeout($credential->resolveTimeout())
                ->connectTimeout($credential->resolveConnectTimeout())
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

        $updateData = ['provider_payload' => $providerPayload];

        $rawStatus = strtolower(trim((string) data_get($response, 'status', '')));

        if (in_array($rawStatus, ['failed', 'undelivered'], true) && $message->status !== WhatsAppMessage::STATUS_FAILED) {
            $updateData['status'] = WhatsAppMessage::STATUS_FAILED;
            $updateData['last_error'] = data_get($response, 'error_message')
                ?: 'Twilio status: '.$rawStatus.' (error_code: '.data_get($response, 'error_code', 'N/A').')';
        }

        $message->update($updateData);

        return $message;
    }

    private function shouldPollTwilio(WhatsAppMessage $message, bool $force = false): bool
    {
        if ((string) data_get($message->provider_payload, 'provider') !== 'twilio') {
            return false;
        }

        if (! filled($message->provider_message_id)) {
            return false;
        }

        if ($this->messageWasRead($message)) {
            return false;
        }

        if ($force) {
            return true;
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

        $appointmentIds = $groupedMessages->keys()->all();
        $appointments = Appointment::query()->whereIn('id', $appointmentIds)->get()->keyBy('id');

        $updated = 0;

        foreach ($groupedMessages as $appointmentId => $appointmentMessages) {
            $appointment = $appointments->get($appointmentId);

            if (! $appointment) {
                continue;
            }

            $statusMessages = $appointmentMessages
                ->reject(fn (WhatsAppMessage $message): bool => in_array(data_get($message->metadata, 'twilio_template_scope'), [
                    WhatsAppSender::TEMPLATE_SCOPE_APPOINTMENT_CREATED,
                ], true))
                ->values();

            if ($statusMessages->isEmpty()) {
                continue;
            }

            $sentAt = $this->latestTimestamp($statusMessages->map(fn (WhatsAppMessage $message): ?Carbon => $message->sent_at));
            $deliveredAt = $this->latestTimestamp($statusMessages->map(fn (WhatsAppMessage $message): ?Carbon => $message->deliveredAt()));
            $readAt = $this->latestTimestamp($statusMessages->map(fn (WhatsAppMessage $message): ?Carbon => $message->readAt()));

            $newEnviado = $appointment->enviado || $sentAt !== null;
            $newActivo = $newEnviado ? false : $appointment->activo;
            $newSentAt = $this->latestTimestamp(collect([$appointment->whatsapp_sent_at, $sentAt]));
            $newEntregado = $appointment->entregado || $deliveredAt !== null;
            $newDeliveredAt = $this->latestTimestamp(collect([$appointment->whatsapp_delivered_at, $deliveredAt]));
            $newReadAt = $this->latestTimestamp(collect([$appointment->whatsapp_read_at, $readAt]));

            $dirty = $newEnviado !== $appointment->enviado
                || $newActivo !== $appointment->activo
                || $this->timestampDiffers($appointment->whatsapp_sent_at, $newSentAt)
                || $newEntregado !== $appointment->entregado
                || $this->timestampDiffers($appointment->whatsapp_delivered_at, $newDeliveredAt)
                || $this->timestampDiffers($appointment->whatsapp_read_at, $newReadAt);

            if ($dirty) {
                $appointment->update([
                    'enviado' => $newEnviado,
                    'activo' => $newActivo,
                    'whatsapp_sent_at' => $newSentAt,
                    'entregado' => $newEntregado,
                    'whatsapp_delivered_at' => $newDeliveredAt,
                    'whatsapp_read_at' => $newReadAt,
                ]);

                $updated++;
            }
        }

        return $updated;
    }

    private function timestampDiffers(?Carbon $current, ?Carbon $new): bool
    {
        if ($current === null && $new === null) {
            return false;
        }

        if ($current === null || $new === null) {
            return true;
        }

        return $current->ne($new);
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

    private function messageWasRead(WhatsAppMessage $message): bool
    {
        $callbackStatus = strtolower(trim((string) data_get($message->provider_payload, 'callback.message_status', '')));
        $callbackEventType = strtoupper(trim((string) data_get($message->provider_payload, 'callback.event_type', '')));
        $rawStatus = strtolower(trim((string) data_get($message->provider_payload, 'raw.status', '')));

        return $callbackStatus === 'read'
            || $callbackEventType === 'READ'
            || $rawStatus === 'read';
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
