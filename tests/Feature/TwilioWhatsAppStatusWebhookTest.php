<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Twilio\Security\RequestValidator;

class TwilioWhatsAppStatusWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_twilio_status_callback_marks_the_appointment_as_delivered(): void
    {
        Carbon::setTestNow('2026-06-23 10:00:00');

        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.status_callback_url', route('webhooks.twilio.whatsapp-status', absolute: true));

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'entregado' => false,
            'activo' => true,
        ]);

        WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Hola Ana',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'direction' => WhatsAppMessage::DIRECTION_OUTBOUND,
            'status' => WhatsAppMessage::STATUS_SENT,
            'provider_message_id' => 'SM123456789',
            'provider_payload' => [
                'provider' => 'twilio',
                'payload' => [
                    'to' => 'whatsapp:+34600111222',
                ],
                'raw' => [
                    'sid' => 'SM123456789',
                    'status' => 'sent',
                ],
            ],
        ]);

        $payload = [
            'AccountSid' => 'AC123',
            'MessageSid' => 'SM123456789',
            'MessageStatus' => 'delivered',
            'EventType' => 'DELIVERED',
            'To' => 'whatsapp:+34600111222',
            'From' => 'whatsapp:+14155238886',
        ];

        $signature = (new RequestValidator('test-token'))->computeSignature(
            route('webhooks.twilio.whatsapp-status', absolute: true),
            $payload
        );

        $this->post(route('webhooks.twilio.whatsapp-status'), $payload, [
            'X-Twilio-Signature' => $signature,
        ])->assertNoContent();

        $appointment->refresh();
        $message = WhatsAppMessage::query()->firstOrFail()->refresh();

        $this->assertTrue($appointment->entregado);
        $this->assertNotNull($appointment->whatsapp_delivered_at);
        $this->assertNull($appointment->whatsapp_read_at);
        $this->assertSame('delivered', $message->provider_payload['callback']['message_status']);
        $this->assertSame('DELIVERED', $message->provider_payload['callback']['event_type']);
        $this->assertSame('SM123456789', $message->provider_message_id);

        Carbon::setTestNow();
    }

    public function test_twilio_inbound_creates_new_inbound_record(): void
    {
        Carbon::setTestNow('2026-06-23 10:00:00');

        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.status_callback_url', route('webhooks.twilio.whatsapp-status', absolute: true));

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'entregado' => false,
            'activo' => true,
        ]);

        $outbound = WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Hola Ana',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'direction' => WhatsAppMessage::DIRECTION_OUTBOUND,
            'status' => WhatsAppMessage::STATUS_SENT,
            'provider_message_id' => 'SM123456789',
            'provider_payload' => [
                'provider' => 'twilio',
                'payload' => ['to' => 'whatsapp:+34600111222'],
                'raw' => ['sid' => 'SM123456789', 'status' => 'sent'],
            ],
        ]);

        $payload = [
            'AccountSid' => 'AC123',
            'MessageSid' => 'SM_INBOUND_001',
            'Direction' => 'inbound-api',
            'Status' => 'received',
            'Body' => 'Necesito reprogramar la cita',
            'To' => 'whatsapp:+14155238886',
            'From' => 'whatsapp:+34600111222',
        ];

        $signature = (new RequestValidator('test-token'))->computeSignature(
            route('webhooks.twilio.whatsapp-status', absolute: true),
            $payload
        );

        $this->post(route('webhooks.twilio.whatsapp-status'), $payload, [
            'X-Twilio-Signature' => $signature,
        ])->assertNoContent();

        $inbound = WhatsAppMessage::query()
            ->where('direction', WhatsAppMessage::DIRECTION_INBOUND)
            ->firstOrFail();

        $this->assertSame($outbound->id, $inbound->parent_id);
        $this->assertSame($appointment->id, $inbound->appointment_id);
        $this->assertSame('Necesito reprogramar la cita', $inbound->respuesta);
        $this->assertSame('inbound-api', $inbound->provider_payload['inbound']['direction']);
        $this->assertSame('received', $inbound->provider_payload['inbound']['status']);
        $this->assertSame('Necesito reprogramar la cita', $inbound->provider_payload['inbound']['body']);

        $outbound->refresh();
        $this->assertNull($outbound->respuesta);

        Carbon::setTestNow();
    }

    public function test_twilio_inbound_with_parent_message_sid_links_to_correct_message(): void
    {
        Carbon::setTestNow('2026-06-23 10:00:00');

        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.status_callback_url', route('webhooks.twilio.whatsapp-status', absolute: true));

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Perez',
            'telefono' => '+34600111222',
        ]);

        $olderMessage = WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'nombre' => 'Ana',
            'apellidos' => 'Perez',
            'telefono' => '+34600111222',
            'scheduled_for' => now()->subDays(2),
            'message' => 'Mensaje viejo',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'direction' => WhatsAppMessage::DIRECTION_OUTBOUND,
            'status' => WhatsAppMessage::STATUS_SENT,
            'sent_at' => now()->subDays(2),
            'provider_message_id' => 'SM_OLDER_001',
            'provider_payload' => ['provider' => 'twilio'],
        ]);

        $targetMessage = WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'nombre' => 'Ana',
            'apellidos' => 'Perez',
            'telefono' => '+34600111222',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Mensaje nuevo',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'direction' => WhatsAppMessage::DIRECTION_OUTBOUND,
            'status' => WhatsAppMessage::STATUS_SENT,
            'sent_at' => now()->subMinute(),
            'provider_message_id' => 'SM_TARGET_002',
            'provider_payload' => ['provider' => 'twilio'],
        ]);

        $payload = [
            'AccountSid' => 'AC123',
            'MessageSid' => 'SM_INBOUND_003',
            'Direction' => 'inbound-api',
            'Status' => 'received',
            'Body' => 'Confirmar',
            'To' => 'whatsapp:+14155238886',
            'From' => 'whatsapp:+34600111222',
            'ParentMessageSid' => 'SM_TARGET_002',
            'ConversationSid' => 'CH_CONVERSATION_001',
        ];

        $signature = (new RequestValidator('test-token'))->computeSignature(
            route('webhooks.twilio.whatsapp-status', absolute: true),
            $payload
        );

        $this->post(route('webhooks.twilio.whatsapp-status'), $payload, [
            'X-Twilio-Signature' => $signature,
        ])->assertNoContent();

        $inbound = WhatsAppMessage::query()
            ->where('direction', WhatsAppMessage::DIRECTION_INBOUND)
            ->firstOrFail();

        $this->assertSame($targetMessage->id, $inbound->parent_id);
        $this->assertSame('SM_TARGET_002', $inbound->provider_payload['inbound']['parent_message_sid']);
        $this->assertSame('CH_CONVERSATION_001', $inbound->provider_payload['inbound']['conversation_sid']);

        $olderMessage->refresh();
        $this->assertNull($olderMessage->respuesta);

        Carbon::setTestNow();
    }

    public function test_twilio_inbound_without_parent_message_sid_falls_back_to_phone_latest(): void
    {
        Carbon::setTestNow('2026-06-23 10:00:00');

        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.status_callback_url', route('webhooks.twilio.whatsapp-status', absolute: true));

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Perez',
            'telefono' => '+34600111222',
        ]);

        $outbound = WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'nombre' => 'Ana',
            'apellidos' => 'Perez',
            'telefono' => '+34600111222',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Mensaje',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'direction' => WhatsAppMessage::DIRECTION_OUTBOUND,
            'status' => WhatsAppMessage::STATUS_SENT,
            'sent_at' => now()->subMinute(),
            'provider_message_id' => 'SM_NO_PARENT_001',
            'provider_payload' => ['provider' => 'twilio'],
        ]);

        $payload = [
            'AccountSid' => 'AC123',
            'MessageSid' => 'SM_INBOUND_NO_PARENT',
            'Direction' => 'inbound-api',
            'Status' => 'received',
            'Body' => 'Gracias',
            'To' => 'whatsapp:+14155238886',
            'From' => 'whatsapp:+34600111222',
        ];

        $signature = (new RequestValidator('test-token'))->computeSignature(
            route('webhooks.twilio.whatsapp-status', absolute: true),
            $payload
        );

        $this->post(route('webhooks.twilio.whatsapp-status'), $payload, [
            'X-Twilio-Signature' => $signature,
        ])->assertNoContent();

        $inbound = WhatsAppMessage::query()
            ->where('direction', WhatsAppMessage::DIRECTION_INBOUND)
            ->firstOrFail();

        $this->assertSame($outbound->id, $inbound->parent_id);
        $this->assertSame('Gracias', $inbound->respuesta);
        $this->assertNull($inbound->provider_payload['inbound']['parent_message_sid']);
        $this->assertNull($inbound->provider_payload['inbound']['conversation_sid']);

        Carbon::setTestNow();
    }

    public function test_twilio_inbound_uses_button_text_when_present(): void
    {
        Carbon::setTestNow('2026-06-23 10:00:00');

        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.status_callback_url', route('webhooks.twilio.whatsapp-status', absolute: true));

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Perez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'entregado' => true,
            'activo' => true,
        ]);

        $outbound = WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Perez',
            'telefono' => '+34600111222',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Recordatorio',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'direction' => WhatsAppMessage::DIRECTION_OUTBOUND,
            'status' => WhatsAppMessage::STATUS_SENT,
            'sent_at' => now()->subMinute(),
            'provider_message_id' => 'SM_BUTTON_TEXT_001',
            'provider_payload' => ['provider' => 'twilio'],
        ]);

        $payload = [
            'AccountSid' => 'AC123',
            'MessageSid' => 'SM_INBOUND_BUTTON_TEXT',
            'Direction' => 'inbound-api',
            'Status' => 'received',
            'ButtonText' => 'Confirmar',
            'Body' => '',
            'To' => 'whatsapp:+14155238886',
            'From' => 'whatsapp:+34600111222',
            'ParentMessageSid' => 'SM_BUTTON_TEXT_001',
        ];

        $signature = (new RequestValidator('test-token'))->computeSignature(
            route('webhooks.twilio.whatsapp-status', absolute: true),
            $payload
        );

        $this->post(route('webhooks.twilio.whatsapp-status'), $payload, [
            'X-Twilio-Signature' => $signature,
        ])->assertNoContent();

        $inbound = WhatsAppMessage::query()
            ->where('direction', WhatsAppMessage::DIRECTION_INBOUND)
            ->firstOrFail();
        $appointment->refresh();

        $this->assertSame('Confirmar', $inbound->respuesta);
        $this->assertSame('Confirmar', $inbound->provider_payload['inbound']['button_text']);
        $this->assertSame('Confirmar', $inbound->provider_payload['inbound']['response_text']);
        $this->assertTrue($appointment->confirmada);

        Carbon::setTestNow();
    }

    public function test_twilio_inbound_marks_appointment_confirmed_when_button_text_is_confirmada_without_payload(): void
    {
        Carbon::setTestNow('2026-06-23 10:00:00');

        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.status_callback_url', route('webhooks.twilio.whatsapp-status', absolute: true));

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Perez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'entregado' => true,
            'activo' => true,
        ]);
        $outbound = WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Perez',
            'telefono' => '+34600111222',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Recordatorio',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'direction' => WhatsAppMessage::DIRECTION_OUTBOUND,
            'status' => WhatsAppMessage::STATUS_SENT,
            'sent_at' => now()->subMinute(),
            'provider_message_id' => 'SM_BUTTON_CONFIRMADA',
            'provider_payload' => ['provider' => 'twilio'],
        ]);

        $payload = [
            'AccountSid' => 'AC123',
            'MessageSid' => 'SM_INBOUND_CONFIRMADA',
            'Direction' => 'inbound-api',
            'Status' => 'received',
            'ButtonText' => 'Confirmada',
            'Body' => '',
            'To' => 'whatsapp:+14155238886',
            'From' => 'whatsapp:+34600111222',
            'ParentMessageSid' => 'SM_BUTTON_CONFIRMADA',
        ];

        $signature = (new RequestValidator('test-token'))->computeSignature(
            route('webhooks.twilio.whatsapp-status', absolute: true),
            $payload
        );

        $this->post(route('webhooks.twilio.whatsapp-status'), $payload, [
            'X-Twilio-Signature' => $signature,
        ])->assertNoContent();

        $inbound = WhatsAppMessage::query()
            ->where('direction', WhatsAppMessage::DIRECTION_INBOUND)
            ->firstOrFail();
        $appointment->refresh();

        $this->assertSame('Confirmada', $inbound->respuesta);
        $this->assertTrue($inbound->isConfirmed());
        $this->assertTrue($appointment->confirmada);
        $this->assertFalse($appointment->pendiente_reprogramacion);

        Carbon::setTestNow();
    }

    public function test_twilio_inbound_matches_template_reply_parent_sid_aliases(): void
    {
        Carbon::setTestNow('2026-06-23 10:00:00');

        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.status_callback_url', route('webhooks.twilio.whatsapp-status', absolute: true));

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Perez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'entregado' => true,
            'activo' => true,
        ]);
        $outbound = WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Perez',
            'telefono' => '+34600111222',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Recordatorio',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'direction' => WhatsAppMessage::DIRECTION_OUTBOUND,
            'status' => WhatsAppMessage::STATUS_SENT,
            'sent_at' => now()->subMinute(),
            'provider_message_id' => 'SM_TEMPLATE_PARENT',
            'provider_payload' => ['provider' => 'twilio'],
        ]);

        $payload = [
            'AccountSid' => 'AC123',
            'SmsMessageSid' => 'SM_INBOUND_ALIAS',
            'Direction' => 'inbound-api',
            'Status' => 'received',
            'ButtonText' => 'Confirmar',
            'ButtonPayload' => 'confirmar',
            'To' => 'whatsapp:+14155238886',
            'From' => 'whatsapp:+34600111222',
            'OriginalMessageSid' => 'SM_TEMPLATE_PARENT',
        ];

        $signature = (new RequestValidator('test-token'))->computeSignature(
            route('webhooks.twilio.whatsapp-status', absolute: true),
            $payload
        );

        $this->post(route('webhooks.twilio.whatsapp-status'), $payload, [
            'X-Twilio-Signature' => $signature,
        ])->assertNoContent();

        $inbound = WhatsAppMessage::query()
            ->where('direction', WhatsAppMessage::DIRECTION_INBOUND)
            ->firstOrFail();
        $appointment->refresh();

        $this->assertSame($outbound->id, $inbound->parent_id);
        $this->assertSame('SM_TEMPLATE_PARENT', $inbound->provider_payload['inbound']['parent_message_sid']);
        $this->assertSame('SM_INBOUND_ALIAS', $inbound->provider_payload['inbound']['message_sid']);
        $this->assertTrue($appointment->confirmada);

        Carbon::setTestNow();
    }

    public function test_twilio_inbound_creates_separate_records_for_multiple_responses(): void
    {
        Carbon::setTestNow('2026-06-23 10:00:00');

        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.status_callback_url', route('webhooks.twilio.whatsapp-status', absolute: true));

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Perez',
            'telefono' => '+34600111222',
        ]);
        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '11:30',
            'enviado' => true,
            'entregado' => true,
            'activo' => true,
        ]);

        $outbound = WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Ana',
            'apellidos' => 'Perez',
            'telefono' => '+34600111222',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Recordatorio',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'direction' => WhatsAppMessage::DIRECTION_OUTBOUND,
            'status' => WhatsAppMessage::STATUS_SENT,
            'sent_at' => now()->subMinute(),
            'provider_message_id' => 'SM_MULTI_RESPONSE',
            'provider_payload' => ['provider' => 'twilio'],
        ]);

        $confirmarPayload = [
            'AccountSid' => 'AC123',
            'MessageSid' => 'SM_INBOUND_CONFIRMAR_2',
            'Direction' => 'inbound-api',
            'Status' => 'received',
            'Body' => 'Confirmar',
            'To' => 'whatsapp:+14155238886',
            'From' => 'whatsapp:+34600111222',
            'ParentMessageSid' => 'SM_MULTI_RESPONSE',
        ];

        $signature = (new RequestValidator('test-token'))->computeSignature(
            route('webhooks.twilio.whatsapp-status', absolute: true),
            $confirmarPayload
        );

        $this->post(route('webhooks.twilio.whatsapp-status'), $confirmarPayload, [
            'X-Twilio-Signature' => $signature,
        ])->assertNoContent();

        Carbon::setTestNow('2026-06-23 10:05:00');

        $reprogramarPayload = [
            'AccountSid' => 'AC123',
            'MessageSid' => 'SM_INBOUND_REPROGRAMAR_2',
            'Direction' => 'inbound-api',
            'Status' => 'received',
            'Body' => 'Reprogramar',
            'To' => 'whatsapp:+14155238886',
            'From' => 'whatsapp:+34600111222',
            'ParentMessageSid' => 'SM_MULTI_RESPONSE',
        ];

        $signature2 = (new RequestValidator('test-token'))->computeSignature(
            route('webhooks.twilio.whatsapp-status', absolute: true),
            $reprogramarPayload
        );

        $this->post(route('webhooks.twilio.whatsapp-status'), $reprogramarPayload, [
            'X-Twilio-Signature' => $signature2,
        ])->assertNoContent();

        $inboundMessages = WhatsAppMessage::query()
            ->where('direction', WhatsAppMessage::DIRECTION_INBOUND)
            ->where('parent_id', $outbound->id)
            ->get();

        $this->assertCount(2, $inboundMessages);
        $this->assertSame('Confirmar', $inboundMessages->first()->respuesta);
        $this->assertSame('Reprogramar', $inboundMessages->last()->respuesta);

        $appointment->refresh();
        $this->assertTrue($appointment->pendiente_reprogramacion);
        $this->assertFalse($appointment->confirmada);

        Carbon::setTestNow();
    }

    public function test_twilio_status_callback_rejects_invalid_signatures(): void
    {
        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.status_callback_url', route('webhooks.twilio.whatsapp-status', absolute: true));

        $response = $this->post(route('webhooks.twilio.whatsapp-status'), [
            'MessageSid' => 'SM123456789',
            'MessageStatus' => 'delivered',
        ], [
            'X-Twilio-Signature' => 'invalid-signature',
        ]);

        $response->assertForbidden();
    }
}
