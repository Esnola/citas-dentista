<?php

namespace Tests\Feature;

use App\Livewire\Settings\TwilioContentTemplateSettings;
use App\Models\AppSetting;
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

    public function test_admin_can_save_and_assign_twilio_content_templates_by_use_case(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-08 09:15:00', 'Europe/Madrid'));

        try {
            $admin = User::factory()->create(['is_admin' => true]);
            $firstSid = 'HX'.str_repeat('1', 32);
            $secondSid = 'HX'.str_repeat('2', 32);
            $thirdSid = 'HX'.str_repeat('3', 32);

            Livewire::actingAs($admin)
                ->test(TwilioContentTemplateSettings::class)
                ->set('nombre', 'Recordatorio')
                ->set('contentSid', $firstSid)
                ->set('variablePreset', 'nombre_dia_hora')
                ->call('addTemplate')
                ->set('nombre', 'Confirmación')
                ->set('contentSid', $secondSid)
                ->set('variablePreset', 'nombre_dia_hora')
                ->call('addTemplate')
                ->set('nombre', 'Cambio')
                ->set('contentSid', $thirdSid)
                ->set('variablePreset', 'cambio_cita')
                ->call('addTemplate');

            $second = TwilioContentTemplate::query()->where('content_sid', $secondSid)->firstOrFail();
            $third = TwilioContentTemplate::query()->where('content_sid', $thirdSid)->firstOrFail();

            Livewire::actingAs($admin)
                ->test(TwilioContentTemplateSettings::class)
                ->set('appointmentReminderTemplateId', (string) $second->id)
                ->set('appointmentChangedTemplateId', (string) $third->id)
                ->call('saveAssignments')
                ->assertSet('status', 'Asignaciones de plantillas actualizadas.');

            $this->assertSame($second->id, AppSetting::get()->twilio_template_appointment_reminder_id);
            $this->assertSame($third->id, AppSetting::get()->twilio_template_appointment_changed_id);
            $this->assertSame(
                $secondSid,
                app(WhatsAppSender::class)->twilioContentSid(WhatsAppSender::TEMPLATE_SCOPE_APPOINTMENT_REMINDER),
            );
            $this->assertSame(
                $thirdSid,
                app(WhatsAppSender::class)->twilioContentSid(WhatsAppSender::TEMPLATE_SCOPE_APPOINTMENT_CHANGED),
            );
            $this->assertSame(
                ['1' => '[NOMBRE]', '2' => '[DIA]', '3' => '[HORA]'],
                $second->content_variables,
            );
            $this->assertSame(
                ['1' => '[NOMBRE]', '2' => '[DIA-ANTERIOR]', '3' => '[HORA-ANTERIOR]', '4' => '[HORA-NUEVA]', '5' => '[DIA-NUEVO]'],
                $third->content_variables,
            );
            $preview = app(WhatsAppSender::class)->buildTwilioPreviewRequest('600123123', 'Prueba', forceTemplate: true, templateId: $third->id);

            $this->assertSame(
                $thirdSid,
                $preview['ContentSid'],
            );
            $this->assertSame(
                json_encode([
                    '1' => 'Ana',
                    '2' => '',
                    '3' => '',
                    '4' => '10:30',
                    '5' => 'jueves 9 de julio',
                ], JSON_UNESCAPED_UNICODE),
                $preview['ContentVariables'],
            );
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_no_database_template_means_no_twilio_template_is_resolved(): void
    {
        $this->assertNull(app(WhatsAppSender::class)->twilioContentSid());
    }

    public function test_admin_can_save_message_variable_twilio_content_template(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $sid = 'HX'.str_repeat('4', 32);

        Livewire::actingAs($admin)
            ->test(TwilioContentTemplateSettings::class)
            ->set('nombre', 'Mensaje libre')
            ->set('contentSid', $sid)
            ->set('variablePreset', 'message')
            ->call('addTemplate')
            ->assertSet('status', 'Plantilla guardada.');

        $template = TwilioContentTemplate::query()->where('content_sid', $sid)->firstOrFail();

        $this->assertSame(['1' => '[MENSAJE]'], $template->content_variables);

        $preview = app(WhatsAppSender::class)->buildTwilioPreviewRequest('600123123', 'Texto de prueba', forceTemplate: true, templateId: $template->id);

        $this->assertSame(
            json_encode(['1' => 'Mensaje de prueba'], JSON_UNESCAPED_UNICODE),
            $preview['ContentVariables'],
        );
    }
}
