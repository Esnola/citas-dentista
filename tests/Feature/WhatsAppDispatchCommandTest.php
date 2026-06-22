<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WhatsAppMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppDispatchCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_due_messages_are_sent_via_cloud_api_and_marked_as_sent(): void
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

        Config::set('whatsapp.driver', 'cloud_api');
        Config::set('whatsapp.cloud_api.base_url', 'https://graph.facebook.com');
        Config::set('whatsapp.cloud_api.version', 'v22.0');
        Config::set('whatsapp.cloud_api.phone_number_id', '1234567890');
        Config::set('whatsapp.cloud_api.access_token', 'test-token');
        Config::set('whatsapp.default_country_code', '+34');

        Http::fake([
            'graph.facebook.com/*/messages' => Http::response([
                'messages' => [
                    ['id' => 'wamid.TEST123'],
                ],
            ], 200),
        ]);

        $this->artisan('whatsapp:dispatch-due')->assertExitCode(0);

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://graph.facebook.com/v22.0/1234567890/messages'
                && $request['messaging_product'] === 'whatsapp'
                && $request['to'] === '+34600123123'
                && $request['type'] === 'text';
        });

        $message = WhatsAppMessage::query()->firstOrFail();

        $this->assertSame(WhatsAppMessage::STATUS_SENT, $message->status);
        $this->assertSame('wamid.TEST123', $message->provider_message_id);
        $this->assertSame('cloud_api', $message->provider_payload['provider']);
        $this->assertSame('Hola Ana', $message->provider_payload['payload']['text']['body']);
        $this->assertNotNull($message->sent_at);
    }
}
