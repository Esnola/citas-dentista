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
