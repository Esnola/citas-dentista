<?php

namespace Tests\Feature;

use App\Livewire\Settings\TwilioContentTemplateSettings;
use App\Models\TwilioContentTemplate;
use App\Models\User;
use App\Services\WhatsApp\WhatsAppSender;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TwilioContentTemplateSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_save_and_select_a_twilio_content_template(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-08 09:15:00', 'Europe/Madrid'));

        try {
            $admin = User::factory()->create(['is_admin' => true]);
            $firstSid = 'HX'.str_repeat('1', 32);
            $secondSid = 'HX'.str_repeat('2', 32);

            Livewire::actingAs($admin)
                ->test(TwilioContentTemplateSettings::class)
                ->set('nombre', 'Recordatorio')
                ->set('contentSid', $firstSid)
                ->set('variablePreset', 'with_name')
                ->call('addTemplate')
                ->set('nombre', 'Confirmación')
                ->set('contentSid', $secondSid)
                ->set('variablePreset', 'appointment')
                ->call('addTemplate');

            $second = TwilioContentTemplate::query()->where('content_sid', $secondSid)->firstOrFail();

            Livewire::actingAs($admin)
                ->test(TwilioContentTemplateSettings::class)
                ->assertSee('Usar plantilla')
                ->call('selectTemplate', $second->id)
                ->assertSet('status', 'Plantilla seleccionada.');

            $this->assertSame($secondSid, app(WhatsAppSender::class)->twilioContentSid());
            $this->assertSame(
                ['1' => '[DIA]', '2' => '[HORA]'],
                $second->content_variables,
            );
            $preview = app(WhatsAppSender::class)->buildTwilioPreviewRequest('600123123', 'Prueba', forceTemplate: true);

            $this->assertSame(
                $secondSid,
                $preview['ContentSid'],
            );
            $this->assertSame(
                json_encode([
                    '1' => 'jueves 9 de julio',
                    '2' => '10:30',
                ], JSON_UNESCAPED_UNICODE),
                $preview['ContentVariables'],
            );
            $this->assertTrue($second->fresh()->seleccionada);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_env_content_sid_is_used_when_no_database_template_is_selected(): void
    {
        config()->set('whatsapp.twilio.content_sid', 'HX'.str_repeat('a', 32));

        $this->assertSame('HX'.str_repeat('a', 32), app(WhatsAppSender::class)->twilioContentSid());
    }
}
