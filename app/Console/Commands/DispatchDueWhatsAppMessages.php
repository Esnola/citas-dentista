<?php

namespace App\Console\Commands;

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
        $count = 0;

        WhatsAppMessage::due()
            ->chunkById(100, function ($messages) use (&$count, $sender): void {
                foreach ($messages as $message) {
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

        $this->info(sprintf('Processed %d due message(s).', $count));

        return self::SUCCESS;
    }
}
