<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WhatsAppMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppTwilioDispatchTest extends TestCase
{
    use RefreshDatabase;

    public function test_due_messages_are_sent_via_twilio_and_marked_as_sent(): void
    {
        $admin = User::factory()->create();

        WhatsAppMessage::query()->create([
            'user_id' => $admin->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '600123123',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Hola Ana',
            'source' => WhatsAppMessage::SOURCE_MANUAL,
            'status' => WhatsAppMessage::STATUS_PENDING,
        ]);

        Config::set('whatsapp.driver', 'twilio');
        Config::set('whatsapp.twilio.account_sid', 'AC123');
        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');
        Config::set('whatsapp.default_country_code', '+34');

        Http::fake([
            'api.twilio.com/*/Messages.json' => Http::response([
                'sid' => 'SMTEST123',
                'status' => 'queued',
                'to' => 'whatsapp:+34600123123',
                'from' => 'whatsapp:+14155238886',
            ], 201),
        ]);

        $this->artisan('whatsapp:dispatch-due')->assertExitCode(0);

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://api.twilio.com/2010-04-01/Accounts/AC123/Messages.json'
                && $request['From'] === 'whatsapp:+14155238886'
                && $request['To'] === 'whatsapp:+34600123123'
                && $request['Body'] === 'Hola Ana';
        });

        $message = WhatsAppMessage::query()->firstOrFail();

        $this->assertSame(WhatsAppMessage::STATUS_SENT, $message->status);
        $this->assertSame('SMTEST123', $message->provider_message_id);
        $this->assertSame('twilio', $message->provider_payload['provider']);
        $this->assertSame('Hola Ana', $message->provider_payload['payload']['body']);
        $this->assertSame('whatsapp:+34600123123', $message->provider_payload['payload']['to']);
        $this->assertNotNull($message->sent_at);
    }
}
