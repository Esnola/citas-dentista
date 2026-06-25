<?php

namespace Tests\Feature;

use App\Livewire\WhatsAppConnectionTest;
use App\Services\WhatsApp\WhatsAppSender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class WhatsAppConnectionTestComponentTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_connection_form_calls_sender_and_shows_success(): void
    {
        $sender = Mockery::mock(WhatsAppSender::class);
        $sender->shouldReceive('sendTestMessage')
            ->once()
            ->with('+34600123123', 'Mensaje de prueba', 'service')
            ->andReturn([
                'provider' => 'twilio',
                'message_id' => 'SMTEST999',
                'payload' => [
                    'to' => 'whatsapp:+34600123123',
                    'body' => 'Mensaje de prueba',
                    'mode' => 'service',
                ],
                'raw' => [],
            ]);

        $this->app->instance(WhatsAppSender::class, $sender);

        Livewire::test(WhatsAppConnectionTest::class)
            ->set('mode', 'service')
            ->set('recipient', '+34600123123')
            ->set('body', 'Mensaje de prueba')
            ->call('sendTest')
            ->assertSet('statusType', 'success')
            ->assertSet('status', 'Prueba enviada correctamente.')
            ->assertSet('details.message_id', 'SMTEST999')
            ->assertSet('details.provider', 'twilio')
            ->assertSet('details.mode', 'service');
    }

    public function test_settings_connection_form_can_send_to_saved_recipient(): void
    {
        config()->set('whatsapp.twilio.mode', 'sandbox');

        $sender = Mockery::mock(WhatsAppSender::class);
        $sender->shouldReceive('twilioTestRecipient')->once()->andReturn('+34600123123');
        $sender->shouldReceive('sendTestMessage')
            ->once()
            ->with('+34600123123', 'Mensaje de prueba desde Clínica Dental Eugenia.', 'sandbox')
            ->andReturn([
                'provider' => 'twilio',
                'message_id' => 'SMTEST777',
                'payload' => [
                    'to' => 'whatsapp:+34600123123',
                    'body' => 'Mensaje de prueba desde Clínica Dental Eugenia.',
                    'mode' => 'sandbox',
                ],
                'raw' => [],
            ]);

        $this->app->instance(WhatsAppSender::class, $sender);

        Livewire::test(WhatsAppConnectionTest::class)
            ->call('sendSavedRecipient')
            ->assertSet('statusType', 'success')
            ->assertSet('details.message_id', 'SMTEST777')
            ->assertSet('details.to', 'whatsapp:+34600123123')
            ->assertSet('details.mode', 'sandbox');
    }

    public function test_settings_connection_form_shows_payload_preview_for_twilio_service_mode(): void
    {
        $this->app->setLocale('es');

        config()->set('whatsapp.driver', 'twilio');
        config()->set('whatsapp.twilio.from', 'whatsapp:+14155238886');
        config()->set('whatsapp.twilio.messaging_service_sid', 'MG123');
        config()->set('whatsapp.default_country_code', '+34');

        Livewire::test(WhatsAppConnectionTest::class)
            ->set('mode', 'service')
            ->set('recipient', '600123123')
            ->set('body', 'Mensaje de prueba')
            ->assertSee('Vista previa del payload')
            ->assertSee('MessagingServiceSid')
            ->assertSee('whatsapp:+34600123123')
            ->assertSee('Mensaje de prueba');
    }

    public function test_settings_connection_form_reflects_auto_twilio_mode_from_configuration(): void
    {
        config()->set('whatsapp.driver', 'twilio');
        config()->set('whatsapp.twilio.mode', 'auto');
        config()->set('whatsapp.twilio.from', 'whatsapp:+14155238886');
        config()->set('whatsapp.twilio.messaging_service_sid', 'MG123');
        config()->set('whatsapp.default_country_code', '+34');

        Livewire::test(WhatsAppConnectionTest::class)
            ->assertSet('mode', 'auto')
            ->set('recipient', '600123123')
            ->assertSee('auto → service')
            ->assertSee('MessagingServiceSid')
            ->assertSee('MG123')
            ->assertSee('whatsapp:+34600123123');
    }

    public function test_settings_connection_form_shows_twilio_template_payload_preview(): void
    {
        config()->set('whatsapp.driver', 'twilio');
        config()->set('whatsapp.message_mode', 'template');
        config()->set('whatsapp.twilio.mode', 'sender');
        config()->set('whatsapp.twilio.from', 'whatsapp:+15551234567');
        config()->set('whatsapp.twilio.content_sid', 'HXCONTENT123');
        config()->set('whatsapp.twilio.content_variables', [
            '1' => '[MENSAJE]',
        ]);
        config()->set('whatsapp.default_country_code', '+34');

        Livewire::test(WhatsAppConnectionTest::class)
            ->set('mode', 'sender')
            ->set('recipient', '600123123')
            ->set('body', 'Mensaje de plantilla')
            ->assertSee('ContentSid')
            ->assertSee('HXCONTENT123')
            ->assertSee('ContentVariables')
            ->assertSee('Mensaje de plantilla')
            ->assertDontSee('&quot;Body&quot;');
    }

    public function test_settings_connection_form_does_not_duplicate_country_code_for_whatsapp_recipient(): void
    {
        config()->set('whatsapp.driver', 'twilio');
        config()->set('whatsapp.twilio.from', 'whatsapp:+14155238886');
        config()->set('whatsapp.default_country_code', '+34');

        Livewire::test(WhatsAppConnectionTest::class)
            ->set('mode', 'sandbox')
            ->set('recipient', 'whatsapp:+34618287914')
            ->assertSee('whatsapp:+34618287914')
            ->assertDontSee('whatsapp:+3434618287914');
    }

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('whatsapp.message_mode', 'text');
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
