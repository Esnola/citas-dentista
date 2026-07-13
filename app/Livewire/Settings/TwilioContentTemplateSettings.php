<?php

namespace App\Livewire\Settings;

use App\Models\AppSetting;
use App\Models\TwilioContentTemplate;
use Livewire\Component;

class TwilioContentTemplateSettings extends Component
{
    public string $nombre = '';

    public string $contentSid = '';

    public string $variablePreset = 'with_name';

    public string $status = '';

    public ?int $templatePendingDeletion = null;

    public string $appointmentReminderTemplateId = '';

    public string $appointmentCreatedTemplateId = '';

    public function mount(): void
    {
        $settings = AppSetting::get();
        $defaultTemplateId = (string) (TwilioContentTemplate::query()->min('id') ?? '');

        $this->appointmentReminderTemplateId = (string) ($settings->twilio_template_appointment_reminder_id ?? $defaultTemplateId);
        $this->appointmentCreatedTemplateId = (string) ($settings->twilio_template_appointment_created_id ?? $defaultTemplateId);
    }

    public function addTemplate(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $data = $this->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'contentSid' => ['required', 'string', 'regex:/^HX[a-fA-F0-9]{32}$/', 'unique:twilio_content_templates,content_sid'],
            'variablePreset' => ['required', 'in:with_name,appointment'],
        ]);

        TwilioContentTemplate::query()->create([
            'nombre' => $data['nombre'],
            'content_sid' => $data['contentSid'],
            'content_variables' => $this->variablePresets()[$data['variablePreset']],
        ]);

        $this->reset('nombre', 'contentSid', 'variablePreset');
        $this->status = 'Plantilla guardada.';
        $this->dispatch('templateChanged');
    }

    public function saveAssignments(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $data = $this->validate([
            'appointmentReminderTemplateId' => ['nullable', 'integer', 'exists:twilio_content_templates,id'],
            'appointmentCreatedTemplateId' => ['nullable', 'integer', 'exists:twilio_content_templates,id'],
        ]);

        AppSetting::get()->update([
            'twilio_template_appointment_reminder_id' => $data['appointmentReminderTemplateId'] !== '' ? $data['appointmentReminderTemplateId'] : null,
            'twilio_template_appointment_created_id' => $data['appointmentCreatedTemplateId'] !== '' ? $data['appointmentCreatedTemplateId'] : null,
        ]);

        $this->status = 'Asignaciones de plantillas actualizadas.';
        $this->dispatch('templateChanged');
    }

    public function deleteTemplate(int $templateId): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $template = TwilioContentTemplate::query()->findOrFail($templateId);
        $template->delete();

        if ((int) $this->appointmentReminderTemplateId === $templateId) {
            $this->appointmentReminderTemplateId = '';
        }

        if ((int) $this->appointmentCreatedTemplateId === $templateId) {
            $this->appointmentCreatedTemplateId = '';
        }

        $this->status = 'Plantilla eliminada.';
        $this->dispatch('templateChanged');
        $this->templatePendingDeletion = null;
    }

    public function confirmDeleteTemplate(int $templateId): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $this->templatePendingDeletion = $templateId;
    }

    public function cancelDeleteTemplate(): void
    {
        $this->templatePendingDeletion = null;
    }

    public function render()
    {
        $templates = TwilioContentTemplate::query()->orderBy('id')->get();

        return view('settings.twilio-content-template-settings', [
            'templates' => $templates,
            'pendingTemplate' => $templates->firstWhere('id', $this->templatePendingDeletion),
            'assignedAppointmentReminderTemplate' => $templates->firstWhere('id', (int) $this->appointmentReminderTemplateId),
            'assignedAppointmentCreatedTemplate' => $templates->firstWhere('id', (int) $this->appointmentCreatedTemplateId),
        ]);
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function variablePresets(): array
    {
        return [
            'with_name' => ['1' => '[NOMBRE]', '2' => '[DIA]', '3' => '[HORA]'],
            'appointment' => ['1' => '[DIA]', '2' => '[HORA]'],
        ];
    }
}
