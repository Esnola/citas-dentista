<?php

namespace App\Livewire;

use App\Models\TwilioContentTemplate;
use Livewire\Component;

class TwilioContentTemplateSettings extends Component
{
    public string $nombre = '';

    public string $contentSid = '';

    public string $status = '';

    public function addTemplate(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $data = $this->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'contentSid' => ['required', 'string', 'regex:/^HX[a-fA-F0-9]{32}$/', 'unique:twilio_content_templates,content_sid'],
        ]);

        $template = TwilioContentTemplate::query()->create([
            'nombre' => $data['nombre'],
            'content_sid' => $data['contentSid'],
        ]);

        if (! TwilioContentTemplate::query()->where('seleccionada', true)->exists()) {
            $template->select();
        }

        $this->reset('nombre', 'contentSid');
        $this->status = 'Plantilla guardada.';
    }

    public function selectTemplate(int $templateId): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        TwilioContentTemplate::query()->findOrFail($templateId)->select();
        $this->status = 'Plantilla seleccionada.';
    }

    public function deleteTemplate(int $templateId): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $template = TwilioContentTemplate::query()->findOrFail($templateId);
        $template->delete();

        $this->status = 'Plantilla eliminada.';
    }

    public function render()
    {
        return view('livewire.twilio-content-template-settings', [
            'templates' => TwilioContentTemplate::query()->orderBy('nombre')->get(),
            'envContentSid' => (string) config('whatsapp.twilio.content_sid', ''),
        ]);
    }
}
