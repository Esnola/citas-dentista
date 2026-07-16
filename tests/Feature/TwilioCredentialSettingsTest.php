<?php

namespace Tests\Feature;

use App\Livewire\Settings\TwilioCredentialSettings;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

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
            ->assertSet('status', "HTTP 500 error\nEl endpoint devolvió HTML. Revisa que la URL apunte al webhook de Twilio y no a una página de la aplicación.")
            ->assertDontSee('<!DOCTYPE html>', false);
    }
}
