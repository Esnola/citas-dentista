<?php

namespace Tests\Feature;

use App\Livewire\Settings\WhatsAppConnectionTest;
use App\Models\TwilioContentTemplate;
use App\Models\WhatsAppCredential;
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
            ->with('+34600123123', 'Mensaje de prueba', 'sender', false, null)
            ->andReturn([
                'provider' => 'twilio',
                'message_id' => 'SMTEST999',
                'payload' => [
                    'to' => 'whatsapp:+34600123123',
                    'body' => 'Mensaje de prueba',
                    'mode' => 'sender',
                ],
                'raw' => [],
            ]);

        $this->app->instance(WhatsAppSender::class, $sender);

        Livewire::test(WhatsAppConnectionTest::class)
            ->set('mode', 'sender')
            ->set('recipient', '+34600123123')
            ->set('body', 'Mensaje de prueba')
            ->call('sendTest')
            ->assertSet('statusType', 'success')
            ->assertSet('status', 'Prueba enviada correctamente.')
            ->assertSet('details.message_id', 'SMTEST999')
            ->assertSet('details.provider', 'twilio')
            ->assertSet('details.mode', 'sender');
    }

    public function test_settings_connection_form_can_send_a_template_test_message(): void
    {
        $template = TwilioContentTemplate::query()->create([
            'nombre' => 'Recordatorio',
            'content_sid' => 'HX'.str_repeat('1', 32),
            'content_variables' => [
                '1' => '[NOMBRE]',
                '2' => '[DIA]',
            ],
            'seleccionada' => true,
        ]);

        $sender = Mockery::mock(WhatsAppSender::class);
        $sender->shouldReceive('sendTestMessage')
            ->once()
            ->with('+34600123123', 'Mensaje de prueba', 'sender', true, $template->id)
            ->andReturn([
                'provider' => 'twilio',
                'message_id' => 'SMTESTTEMPLATE',
                'payload' => [
                    'to' => 'whatsapp:+34600123123',
                    'body' => 'Mensaje de prueba',
                    'mode' => 'sender',
                    'content_sid' => $template->content_sid,
                ],
                'raw' => [],
            ]);

        $this->app->instance(WhatsAppSender::class, $sender);

        Livewire::test(WhatsAppConnectionTest::class)
            ->set('mode', 'sender')
            ->set('testType', 'template')
            ->set('templateId', (string) $template->id)
            ->set('recipient', '+34600123123')
            ->set('body', 'Mensaje de prueba')
            ->call('sendTest')
            ->assertSet('statusType', 'success')
            ->assertSet('status', 'Prueba enviada correctamente.')
            ->assertSet('details.message_id', 'SMTESTTEMPLATE')
            ->assertSet('details.provider', 'twilio')
            ->assertSet('details.mode', 'sender');
    }

    public function test_settings_connection_form_blocks_saved_recipient_when_it_is_a_sender_number(): void
    {
        config()->set('whatsapp.twilio.mode', 'sandbox');
        config()->set('whatsapp.twilio.test_recipient', '600123123');

        $credential = WhatsAppCredential::create([
            'mode' => 'sandbox',
            'selected' => true,
        ]);

        $credential->senderNumbers()->create([
            'name' => 'Sandbox',
            'prefix' => '+34',
            'number' => '600123123',
            'selected' => true,
        ]);

        $sender = Mockery::mock(WhatsAppSender::class);
        $sender->shouldNotReceive('sendTestMessage');

        $this->app->instance(WhatsAppSender::class, $sender);

        Livewire::test(WhatsAppConnectionTest::class)
            ->call('sendSavedRecipient')
            ->assertSet('statusType', 'error')
            ->assertSet('status', 'No puedes enviar una prueba a un número que ya está configurado como remitente.');
    }

    public function test_settings_connection_form_blocks_test_messages_to_sender_numbers(): void
    {
        $credential = WhatsAppCredential::create([
            'mode' => 'sandbox',
            'selected' => true,
        ]);

        $credential->senderNumbers()->create([
            'name' => 'Sandbox',
            'prefix' => '+34',
            'number' => '600123123',
            'selected' => true,
        ]);

        $sender = Mockery::mock(WhatsAppSender::class);
        $sender->shouldNotReceive('sendTestMessage');

        $this->app->instance(WhatsAppSender::class, $sender);

        Livewire::test(WhatsAppConnectionTest::class)
            ->set('mode', 'sandbox')
            ->set('recipient', '600123123')
            ->set('body', 'Mensaje de prueba')
            ->call('sendTest')
            ->assertSet('statusType', 'error')
            ->assertSet('status', 'No puedes enviar una prueba a un número que ya está configurado como remitente.')
            ->assertHasErrors(['recipient']);
    }

    public function test_saved_recipient_is_used_for_test_messages_when_it_is_not_a_sender_number(): void
    {
        config()->set('whatsapp.twilio.test_recipient', '600999999');

        $credential = WhatsAppCredential::create([
            'mode' => 'sandbox',
            'selected' => true,
        ]);

        $credential->senderNumbers()->create([
            'name' => 'Sandbox',
            'prefix' => '+34',
            'number' => '600123123',
            'selected' => true,
        ]);

        $sender = Mockery::mock(WhatsAppSender::class);
        $sender->shouldReceive('sendTestMessage')
            ->once()
            ->with('600999999', 'Mensaje de prueba desde Clínica Dental Eugenia.', 'sandbox', false, null)
            ->andReturn([
                'provider' => 'twilio',
                'message_id' => 'SMTEST123',
                'payload' => [
                    'to' => 'whatsapp:+34600999999',
                    'body' => 'Mensaje de prueba desde Clínica Dental Eugenia.',
                    'mode' => 'sandbox',
                ],
                'raw' => [],
            ]);

        $this->app->instance(WhatsAppSender::class, $sender);

        Livewire::test(WhatsAppConnectionTest::class)
            ->call('sendSavedRecipient')
            ->assertSet('statusType', 'success')
            ->assertSet('details.message_id', 'SMTEST123');
    }

    public function test_settings_connection_form_shows_payload_preview_for_twilio_sender_mode(): void
    {
        $this->app->setLocale('es');

        config()->set('whatsapp.driver', 'twilio');
        config()->set('whatsapp.twilio.from', 'whatsapp:+14155238886');
        config()->set('whatsapp.default_country_code', '+34');

        Livewire::test(WhatsAppConnectionTest::class)
            ->set('mode', 'sender')
            ->set('recipient', '600123123')
            ->set('body', 'Mensaje de prueba')
            ->assertSee('Vista previa del payload')
            ->assertSee('From')
            ->assertSee('whatsapp:+34600123123')
            ->assertSee('Mensaje de prueba');
    }

    public function test_settings_connection_form_reflects_auto_twilio_mode_from_configuration(): void
    {
        config()->set('whatsapp.driver', 'twilio');
        config()->set('whatsapp.twilio.mode', 'auto');
        config()->set('whatsapp.twilio.from', 'whatsapp:+14155238886');
        config()->set('whatsapp.default_country_code', '+34');

        Livewire::test(WhatsAppConnectionTest::class)
            ->assertSet('mode', 'auto')
            ->set('recipient', '600123123')
            ->assertSee('auto → sandbox')
            ->assertSee('From')
            ->assertSee('whatsapp:+34600123123');
    }

    public function test_settings_connection_form_refreshes_preview_from_when_selected_sender_changes(): void
    {
        $credential = WhatsAppCredential::create([
            'mode' => 'sandbox',
            'selected' => true,
        ]);

        $first = $credential->senderNumbers()->create([
            'name' => 'Primero',
            'prefix' => '+34',
            'number' => '600111111',
            'selected' => true,
        ]);

        $second = $credential->senderNumbers()->create([
            'name' => 'Segundo',
            'prefix' => '+34',
            'number' => '600222222',
            'selected' => false,
        ]);

        $component = Livewire::test(WhatsAppConnectionTest::class)
            ->set('mode', 'sandbox')
            ->set('recipient', '600123123');

        $credential->senderNumbers()->update(['selected' => false]);
        $credential->senderNumbers()->where('id', $second->id)->update(['selected' => true]);

        $component
            ->dispatch('credentialsChanged')
            ->assertSee($second->whatsapp_address)
            ->assertDontSee($first->whatsapp_address);
    }

    public function test_sender_numbers_keep_the_same_order_when_selection_changes(): void
    {
        $credential = WhatsAppCredential::create([
            'mode' => 'sandbox',
            'selected' => true,
        ]);

        $first = $credential->senderNumbers()->create([
            'name' => 'Primero',
            'prefix' => '+34',
            'number' => '600111111',
            'selected' => true,
        ]);

        $second = $credential->senderNumbers()->create([
            'name' => 'Segundo',
            'prefix' => '+34',
            'number' => '600222222',
            'selected' => false,
        ]);

        $initialOrder = $credential->senderNumbers()->orderBy('id')->pluck('id')->all();

        $credential->senderNumbers()->update(['selected' => false]);
        $credential->senderNumbers()->where('id', $second->id)->update(['selected' => true]);

        $updatedOrder = $credential->senderNumbers()->orderBy('id')->pluck('id')->all();

        $this->assertSame([$first->id, $second->id], $initialOrder);
        $this->assertSame([$first->id, $second->id], $updatedOrder);
    }

    public function test_settings_connection_form_shows_twilio_template_payload_preview(): void
    {
        $this->app->setLocale('es');

        $template = TwilioContentTemplate::query()->create([
            'nombre' => 'Recordatorio',
            'content_sid' => 'HX'.str_repeat('1', 32),
            'content_variables' => [
                '1' => '[NOMBRE]',
                '2' => '[DIA]',
                '3' => '[HORA]',
            ],
            'seleccionada' => true,
        ]);

        config()->set('whatsapp.driver', 'twilio');
        config()->set('whatsapp.message_mode', 'template');
        config()->set('whatsapp.twilio.mode', 'sender');
        config()->set('whatsapp.twilio.from', 'whatsapp:+15551234567');
        config()->set('whatsapp.twilio.content_sid', 'HX'.str_repeat('2', 32));
        config()->set('whatsapp.default_country_code', '+34');

        Livewire::test(WhatsAppConnectionTest::class)
            ->set('mode', 'sender')
            ->set('testType', 'template')
            ->set('templateId', (string) $template->id)
            ->set('recipient', '600123123')
            ->set('body', 'Mensaje de plantilla')
            ->assertSee('ContentSid')
            ->assertSee('HX'.str_repeat('1', 32))
            ->assertSee('ContentVariables')
            ->assertSee('Ana')
            ->assertSee('Plantilla')
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
