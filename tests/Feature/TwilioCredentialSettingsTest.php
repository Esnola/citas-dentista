<?php

namespace Tests\Feature;

use App\Livewire\Settings\TwilioCredentialSettings;
use App\Models\User;
use App\Models\WhatsAppCredential;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;
use Twilio\Security\RequestValidator;

class TwilioCredentialSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_test_webhook_uses_the_visible_callback_url(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $callbackUrl = 'https://example.com/webhooks/twilio/whatsapp-status';

        Http::fake([
            $callbackUrl => Http::response('', 204),
        ]);

        Livewire::actingAs($admin)
            ->test(TwilioCredentialSettings::class)
            ->set('status_callback_url', $callbackUrl)
            ->call('testWebhook')
            ->assertSet('status', "HTTP 204\nSin contenido.");

        Http::assertSent(fn ($request) => $request->url() === $callbackUrl);
    }

    public function test_test_webhook_does_not_dump_html_responses(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $callbackUrl = 'https://example.com/webhooks/twilio/whatsapp-status';

        Http::fake([
            $callbackUrl => Http::response('<!DOCTYPE html><html><body>Laravel error page</body></html>', 500, [
                'Content-Type' => 'text/html; charset=UTF-8',
            ]),
        ]);

        Livewire::actingAs($admin)
            ->test(TwilioCredentialSettings::class)
            ->set('status_callback_url', $callbackUrl)
            ->call('testWebhook')
            ->assertSet('status', "HTTP 500 error\nEl endpoint devolvió HTML. Revisa que {$callbackUrl} apunte al webhook de Twilio. URL esperada por esta app: ".route('webhooks.twilio.whatsapp-status', absolute: true))
            ->assertDontSee('<!DOCTYPE html>', false);
    }

    public function test_test_webhook_explains_404_responses(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $callbackUrl = 'https://example.com/not-the-webhook';

        Http::fake([
            $callbackUrl => Http::response('<!DOCTYPE html><html><body>Not found</body></html>', 404, [
                'Content-Type' => 'text/html; charset=UTF-8',
            ]),
        ]);

        Livewire::actingAs($admin)
            ->test(TwilioCredentialSettings::class)
            ->set('status_callback_url', $callbackUrl)
            ->call('testWebhook')
            ->assertSet('status', 'HTTP 404 error
No existe un endpoint en esa URL. URL esperada por esta app: '.route('webhooks.twilio.whatsapp-status', absolute: true));
    }

    public function test_test_webhook_explains_403_responses_before_laravel(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $callbackUrl = 'https://example.com/webhooks/twilio/whatsapp-status';

        Http::fake([
            $callbackUrl => Http::response('<!DOCTYPE html><html><body>Forbidden</body></html>', 403, [
                'Content-Type' => 'text/html; charset=UTF-8',
            ]),
        ]);

        Livewire::actingAs($admin)
            ->test(TwilioCredentialSettings::class)
            ->set('status_callback_url', $callbackUrl)
            ->call('testWebhook')
            ->assertSet('status', "HTTP 403 error\nEl servidor bloqueó la petición antes de llegar al webhook Laravel. Revisa reglas de hosting, Cloudflare, mod_security, WAF o protección de POST para {$callbackUrl}.");
    }

    public function test_test_webhook_explains_invalid_twilio_signature(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $callbackUrl = 'https://example.com/webhooks/twilio/whatsapp-status';

        Http::fake([
            $callbackUrl => Http::response('', 403, [
                'X-CitasDentista-Webhook' => 'twilio-whatsapp-status',
            ]),
        ]);

        Livewire::actingAs($admin)
            ->test(TwilioCredentialSettings::class)
            ->set('status_callback_url', $callbackUrl)
            ->call('testWebhook')
            ->assertSet('status', "HTTP 403 error\nLa petición llegó al webhook, pero la firma de Twilio no es válida. Guarda la Callback URL y revisa que el Auth Token coincida con el de Twilio.");
    }

    public function test_test_webhook_signs_the_request_when_auth_token_exists(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $callbackUrl = 'https://example.com/webhooks/twilio/whatsapp-status';

        WhatsAppCredential::get()->update([
            'auth_token' => 'test-token',
        ]);

        Http::fake([
            $callbackUrl => Http::response('', 204),
        ]);

        Livewire::actingAs($admin)
            ->test(TwilioCredentialSettings::class)
            ->set('status_callback_url', $callbackUrl)
            ->call('testWebhook')
            ->assertSet('status', "HTTP 204\nSin contenido.");

        Http::assertSent(function ($request) use ($callbackUrl): bool {
            $expectedSignature = (new RequestValidator('test-token'))
                ->computeSignature($callbackUrl, $request->data());

            return $request->url() === $callbackUrl
                && $request->hasHeader('X-Twilio-Signature', $expectedSignature);
        });
    }
}
