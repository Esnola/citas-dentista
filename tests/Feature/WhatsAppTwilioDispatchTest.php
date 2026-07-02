<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
use App\Services\WhatsApp\WhatsAppSender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppTwilioDispatchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('whatsapp.message_mode', 'text');
    }

    public function test_phone_numbers_are_stored_without_the_spanish_prefix_and_added_for_twilio(): void
    {
        Config::set('whatsapp.default_country_code', '+34');

        $spanishClient = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34 600 123 123',
        ]);
        $foreignClient = Client::query()->create([
            'nombre' => 'Marie',
            'apellidos' => 'Dupont',
            'telefono' => '+33 6 12 34 56 78',
        ]);

        $sender = new WhatsAppSender;

        $this->assertSame('600123123', $spanishClient->telefono);
        $this->assertSame('+33612345678', $foreignClient->telefono);
        $this->assertSame('whatsapp:+34600123123', $sender->buildTwilioPreviewRequest($spanishClient->telefono, 'Hola')['To']);
        $this->assertSame('whatsapp:+33612345678', $sender->buildTwilioPreviewRequest($foreignClient->telefono, 'Bonjour')['To']);
    }

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
        Config::set('whatsapp.twilio.mode', 'sandbox');
        Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');
        Config::set('whatsapp.twilio.status_callback_url', route('webhooks.twilio.whatsapp-status', absolute: true));
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
                && $request['StatusCallback'] === route('webhooks.twilio.whatsapp-status', absolute: true)
                && $request['Body'] === 'Hola Ana';
        });

        $message = WhatsAppMessage::query()->firstOrFail();

        $this->assertSame('600123123', $message->telefono);
        $this->assertSame(WhatsAppMessage::STATUS_SENT, $message->status);
        $this->assertSame('SMTEST123', $message->provider_message_id);
        $this->assertSame('twilio', $message->provider_payload['provider']);
        $this->assertSame('sandbox', $message->provider_payload['payload']['mode']);
        $this->assertSame('Hola Ana', $message->provider_payload['payload']['body']);
        $this->assertSame('whatsapp:+34600123123', $message->provider_payload['payload']['to']);
        $this->assertNotNull($message->sent_at);
    }

    public function test_due_messages_can_be_sent_with_a_twilio_messaging_service(): void
    {
        $admin = User::factory()->create();

        WhatsAppMessage::query()->create([
            'user_id' => $admin->id,
            'nombre' => 'Luis',
            'apellidos' => 'García',
            'telefono' => '611222333',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Hola Luis',
            'source' => WhatsAppMessage::SOURCE_MANUAL,
            'status' => WhatsAppMessage::STATUS_PENDING,
        ]);

        Config::set('whatsapp.driver', 'twilio');
        Config::set('whatsapp.twilio.account_sid', 'AC123');
        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.mode', 'service');
        Config::set('whatsapp.twilio.messaging_service_sid', 'MG123');
        Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');
        Config::set('whatsapp.twilio.status_callback_url', route('webhooks.twilio.whatsapp-status', absolute: true));
        Config::set('whatsapp.default_country_code', '+34');

        Http::fake([
            'api.twilio.com/*/Messages.json' => Http::response([
                'sid' => 'SMTESTSERVICE123',
                'status' => 'queued',
                'to' => 'whatsapp:+34611222333',
            ], 201),
        ]);

        $this->artisan('whatsapp:dispatch-due')->assertExitCode(0);

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://api.twilio.com/2010-04-01/Accounts/AC123/Messages.json'
                && $request['MessagingServiceSid'] === 'MG123'
                && ! isset($request['From'])
                && $request['To'] === 'whatsapp:+34611222333'
                && $request['Body'] === 'Hola Luis';
        });

        $message = WhatsAppMessage::query()->firstOrFail();

        $this->assertSame(WhatsAppMessage::STATUS_SENT, $message->status);
        $this->assertSame('SMTESTSERVICE123', $message->provider_message_id);
        $this->assertSame('service', $message->provider_payload['payload']['mode']);
        $this->assertSame('MG123', $message->provider_payload['payload']['messaging_service_sid']);
        $this->assertNull($message->provider_payload['payload']['from']);
    }

    public function test_auto_twilio_mode_prefers_messaging_service_when_available(): void
    {
        $admin = User::factory()->create();

        WhatsAppMessage::query()->create([
            'user_id' => $admin->id,
            'nombre' => 'Marta',
            'apellidos' => 'Ruiz',
            'telefono' => '622333444',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Hola Marta',
            'source' => WhatsAppMessage::SOURCE_MANUAL,
            'status' => WhatsAppMessage::STATUS_PENDING,
        ]);

        Config::set('whatsapp.driver', 'twilio');
        Config::set('whatsapp.twilio.account_sid', 'AC123');
        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.mode', 'auto');
        Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');
        Config::set('whatsapp.twilio.messaging_service_sid', 'MG456');
        Config::set('whatsapp.twilio.status_callback_url', route('webhooks.twilio.whatsapp-status', absolute: true));
        Config::set('whatsapp.default_country_code', '+34');

        Http::fake([
            'api.twilio.com/*/Messages.json' => Http::response([
                'sid' => 'SMTESTAUTO123',
                'status' => 'queued',
            ], 201),
        ]);

        $this->artisan('whatsapp:dispatch-due')->assertExitCode(0);

        Http::assertSent(function ($request): bool {
            return $request['MessagingServiceSid'] === 'MG456'
                && ! isset($request['From'])
                && $request['To'] === 'whatsapp:+34622333444';
        });

        $message = WhatsAppMessage::query()->firstOrFail();

        $this->assertSame('service', $message->provider_payload['payload']['mode']);
    }

    public function test_due_messages_can_be_sent_with_a_twilio_content_template(): void
    {
        $admin = User::factory()->create();
        $scheduledFor = now()->subMinute();

        WhatsAppMessage::query()->create([
            'user_id' => $admin->id,
            'nombre' => 'Clara',
            'apellidos' => 'Vidal',
            'telefono' => '633444555',
            'scheduled_for' => $scheduledFor,
            'message' => 'Hola Clara',
            'source' => WhatsAppMessage::SOURCE_MANUAL,
            'status' => WhatsAppMessage::STATUS_PENDING,
        ]);

        Config::set('whatsapp.driver', 'twilio');
        Config::set('whatsapp.message_mode', 'template');
        Config::set('whatsapp.twilio.account_sid', 'AC123');
        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.mode', 'sender');
        Config::set('whatsapp.twilio.from', 'whatsapp:+15551234567');
        Config::set('whatsapp.twilio.content_sid', 'HXCONTENT123');
        Config::set('whatsapp.twilio.content_variables', [
            '1' => '[NOMBRE]',
            '2' => '[DIA]',
            '3' => '[HORA]',
            '4' => '[MENSAJE]',
        ]);
        Config::set('whatsapp.twilio.status_callback_url', route('webhooks.twilio.whatsapp-status', absolute: true));
        Config::set('whatsapp.default_country_code', '+34');

        Http::fake([
            'api.twilio.com/*/Messages.json' => Http::response([
                'sid' => 'SMTEMPLATE123',
                'status' => 'queued',
            ], 201),
        ]);

        $this->artisan('whatsapp:dispatch-due')->assertExitCode(0);

        Http::assertSent(function ($request) use ($scheduledFor): bool {
            return $request['From'] === 'whatsapp:+15551234567'
                && $request['To'] === 'whatsapp:+34633444555'
                && $request['ContentSid'] === 'HXCONTENT123'
                && $request['ContentVariables'] === json_encode([
                    '1' => 'Clara',
                    '2' => $scheduledFor->format('d/m/Y'),
                    '3' => $scheduledFor->format('H:i'),
                    '4' => 'Hola Clara',
                ], JSON_UNESCAPED_UNICODE)
                && ! isset($request['Body']);
        });

        $message = WhatsAppMessage::query()->firstOrFail();

        $this->assertSame(WhatsAppMessage::STATUS_SENT, $message->status);
        $this->assertSame('SMTEMPLATE123', $message->provider_message_id);
        $this->assertSame('HXCONTENT123', $message->provider_payload['payload']['content_sid']);
        $this->assertSame('Clara', $message->provider_payload['payload']['content_variables']['1']);
    }

    public function test_twilio_recipient_keeps_existing_whatsapp_prefix_without_duplicating_country_code(): void
    {
        $admin = User::factory()->create();

        WhatsAppMessage::query()->create([
            'user_id' => $admin->id,
            'nombre' => 'Nuria',
            'apellidos' => 'Sanz',
            'telefono' => 'whatsapp:+34618287914',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Hola Nuria',
            'source' => WhatsAppMessage::SOURCE_MANUAL,
            'status' => WhatsAppMessage::STATUS_PENDING,
        ]);

        Config::set('whatsapp.driver', 'twilio');
        Config::set('whatsapp.twilio.account_sid', 'AC123');
        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.mode', 'sandbox');
        Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');
        Config::set('whatsapp.twilio.status_callback_url', route('webhooks.twilio.whatsapp-status', absolute: true));
        Config::set('whatsapp.default_country_code', '+34');

        Http::fake([
            'api.twilio.com/*/Messages.json' => Http::response([
                'sid' => 'SMTESTPREFIX123',
                'status' => 'queued',
            ], 201),
        ]);

        $this->artisan('whatsapp:dispatch-due')->assertExitCode(0);

        Http::assertSent(function ($request): bool {
            return $request['To'] === 'whatsapp:+34618287914';
        });
    }

    public function test_due_messages_use_configured_status_callback_url_when_available(): void
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
        Config::set('whatsapp.twilio.mode', 'sandbox');
        Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');
        Config::set('whatsapp.twilio.status_callback_url', 'https://example.com/webhooks/twilio/whatsapp-status');
        Config::set('whatsapp.default_country_code', '+34');

        Http::fake([
            'api.twilio.com/*/Messages.json' => Http::response([
                'sid' => 'SMTESTCALLBACK123',
                'status' => 'queued',
            ], 201),
        ]);

        $this->artisan('whatsapp:dispatch-due')->assertExitCode(0);

        Http::assertSent(function ($request): bool {
            return $request['StatusCallback'] === 'https://example.com/webhooks/twilio/whatsapp-status';
        });
    }
}
