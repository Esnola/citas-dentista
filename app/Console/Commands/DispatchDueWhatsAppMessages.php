<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\WhatsAppMessage;
use App\Services\WhatsApp\WhatsAppSender;
use Illuminate\Console\Command;
use Throwable;

class DispatchDueWhatsAppMessages extends Command
{
    protected $signature = 'whatsapp:dispatch-due';

    protected $description = 'Dispatch all due WhatsApp messages.';

    public function handle(WhatsAppSender $sender): int
    {
        $queued = $this->queueActiveAppointmentMessages();
        $count = 0;

        WhatsAppMessage::due()
            ->with('appointment')
            ->chunkById(100, function ($messages) use (&$count, $sender): void {
                foreach ($messages as $message) {
                    if ($message->appointment && (! $message->appointment->activo || $message->appointment->enviado)) {
                        continue;
                    }

                    try {
                        $result = $sender->send($message);

                        $message->update([
                            'status' => WhatsAppMessage::STATUS_SENT,
                            'sent_at' => now(),
                            'last_error' => null,
                            'provider_message_id' => $result['message_id'],
                            'provider_payload' => [
                                'provider' => $result['provider'],
                                'payload' => $result['payload'],
                                'raw' => $result['raw'],
                            ],
                        ]);

                        $message->appointment?->update([
                            'enviado' => true,
                        ]);
                    } catch (Throwable $throwable) {
                        $message->update([
                            'status' => WhatsAppMessage::STATUS_FAILED,
                            'last_error' => $throwable->getMessage(),
                        ]);

                        $this->error("Failed to send message {$message->id}: {$throwable->getMessage()}");
                    }

                    $count++;
                }
            });

        $this->info(sprintf('Queued %d appointment message(s).', $queued));
        $this->info(sprintf('Processed %d due message(s).', $count));

        return self::SUCCESS;
    }

    private function queueActiveAppointmentMessages(): int
    {
        $queued = 0;

        Appointment::query()
            ->with('client')
            ->where('activo', true)
            ->where('enviado', false)
            ->whereDoesntHave('whatsAppMessage')
            ->chunkById(100, function ($appointments) use (&$queued): void {
                foreach ($appointments as $appointment) {
                    $client = $appointment->client;

                    if (! $client) {
                        continue;
                    }

                    WhatsAppMessage::query()->create([
                        'client_id' => $client->id,
                        'appointment_id' => $appointment->id,
                        'nombre' => $client->nombre,
                        'apellidos' => $client->apellidos,
                        'telefono' => $client->telefono,
                        'scheduled_for' => $appointment->scheduledFor(),
                        'message' => WhatsAppMessage::buildMessage([
                            'nombre' => $client->nombre,
                            'apellidos' => $client->apellidos,
                            'telefono' => $client->telefono,
                            'scheduled_for' => $appointment->scheduledFor(),
                        ]),
                        'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
                        'status' => WhatsAppMessage::STATUS_PENDING,
                        'metadata' => [
                            'origin_appointment_id' => $appointment->id,
                        ],
                    ]);

                    $queued++;
                }
            });

        return $queued;
    }
}
