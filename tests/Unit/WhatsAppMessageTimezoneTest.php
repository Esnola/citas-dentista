<?php

namespace Tests\Unit;

use App\Models\WhatsAppMessage;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class WhatsAppMessageTimezoneTest extends TestCase
{
    public function test_twilio_utc_timestamp_is_converted_to_application_timezone(): void
    {
        Config::set('app.timezone', 'Europe/Madrid');

        $message = new WhatsAppMessage([
            'provider_payload' => [
                'callback' => [
                    'message_status' => 'delivered',
                    'received_at' => 'Thu, 25 Jun 2026 02:00:49 +0000',
                ],
            ],
        ]);

        $this->assertSame('25/06/2026 04:00', $message->deliveredAt()?->format('d/m/Y H:i'));
    }

    public function test_inbound_api_received_body_is_used_as_the_response_value(): void
    {
        $message = new WhatsAppMessage([
            'provider_payload' => [
                'inbound' => [
                    'direction' => 'inbound-api',
                    'status' => 'received',
                    'body' => 'Reprogramar por la tarde',
                ],
            ],
        ]);

        $this->assertSame('Reprogramar por la tarde', $message->responseValue());
        $this->assertTrue($message->hasResponse());
    }
}
