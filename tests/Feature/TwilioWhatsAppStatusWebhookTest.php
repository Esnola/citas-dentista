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

    public function test_twilio_inbound_api_received_callback_stores_the_body_as_the_response_value(): void
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

        $message = WhatsAppMessage::query()->firstOrFail()->refresh();

        $this->assertSame('Necesito reprogramar la cita', $message->respuesta);
        $this->assertSame('inbound-api', $message->provider_payload['inbound']['direction']);
        $this->assertSame('received', $message->provider_payload['inbound']['status']);
        $this->assertSame('Necesito reprogramar la cita', $message->provider_payload['inbound']['body']);
        $this->assertSame('Necesito reprogramar la cita', $message->responseValue());

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

        $targetMessage->refresh();
        $olderMessage->refresh();

        $this->assertSame('Confirmar', $targetMessage->respuesta);
        $this->assertNotNull($targetMessage->responded_at);
        $this->assertNull($olderMessage->respuesta);

        $this->assertSame('SM_TARGET_002', $targetMessage->provider_payload['inbound']['parent_message_sid']);
        $this->assertSame('CH_CONVERSATION_001', $targetMessage->provider_payload['inbound']['conversation_sid']);

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

        WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'nombre' => 'Ana',
            'apellidos' => 'Perez',
            'telefono' => '+34600111222',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Mensaje',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
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

        $message = WhatsAppMessage::query()->firstOrFail()->refresh();

        $this->assertSame('Gracias', $message->respuesta);
        $this->assertNull($message->provider_payload['inbound']['parent_message_sid']);
        $this->assertNull($message->provider_payload['inbound']['conversation_sid']);

        Carbon::setTestNow();
    }

    public function test_twilio_inbound_overwrites_previous_response_on_same_message(): void
    {
        Carbon::setTestNow('2026-06-23 10:00:00');

        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.status_callback_url', route('webhooks.twilio.whatsapp-status', absolute: true));

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Perez',
            'telefono' => '+34600111222',
        ]);

        $message = WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'nombre' => 'Ana',
            'apellidos' => 'Perez',
            'telefono' => '+34600111222',
            'scheduled_for' => now()->subMinute(),
            'message' => 'Recordatorio',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_SENT,
            'sent_at' => now()->subMinute(),
            'provider_message_id' => 'SM_TARGET_OVERWRITE',
            'provider_payload' => ['provider' => 'twilio'],
        ]);

        $confirmarPayload = [
            'AccountSid' => 'AC123',
            'MessageSid' => 'SM_INBOUND_CONFIRMAR',
            'Direction' => 'inbound-api',
            'Status' => 'received',
            'Body' => 'Confirmar',
            'To' => 'whatsapp:+14155238886',
            'From' => 'whatsapp:+34600111222',
            'ParentMessageSid' => 'SM_TARGET_OVERWRITE',
        ];

        $signature = (new RequestValidator('test-token'))->computeSignature(
            route('webhooks.twilio.whatsapp-status', absolute: true),
            $confirmarPayload
        );

        $this->post(route('webhooks.twilio.whatsapp-status'), $confirmarPayload, [
            'X-Twilio-Signature' => $signature,
        ])->assertNoContent();

        $message->refresh();
        $this->assertSame('Confirmar', $message->respuesta);

        Carbon::setTestNow('2026-06-23 10:05:00');

        $reprogramarPayload = [
            'AccountSid' => 'AC123',
            'MessageSid' => 'SM_INBOUND_REPROGRAMAR',
            'Direction' => 'inbound-api',
            'Status' => 'received',
            'Body' => 'Reprogramar',
            'To' => 'whatsapp:+14155238886',
            'From' => 'whatsapp:+34600111222',
            'ParentMessageSid' => 'SM_TARGET_OVERWRITE',
        ];

        $signature2 = (new RequestValidator('test-token'))->computeSignature(
            route('webhooks.twilio.whatsapp-status', absolute: true),
            $reprogramarPayload
        );

        $this->post(route('webhooks.twilio.whatsapp-status'), $reprogramarPayload, [
            'X-Twilio-Signature' => $signature2,
        ])->assertNoContent();

        $message->refresh();
        $this->assertSame('Reprogramar', $message->respuesta);
        $this->assertSame('Reprogramar', $message->provider_payload['inbound']['body']);

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
