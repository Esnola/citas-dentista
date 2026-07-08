<?php

namespace App\Livewire;

use App\Models\WhatsAppDispatchSettings;
use Livewire\Attributes\On;
use Livewire\Component;

class DispatchBanner extends Component
{
    public bool $enabled = true;

    public function mount(): void
    {
        $this->enabled = WhatsAppDispatchSettings::get()->enabled;
    }

    #[On('dispatchToggled')]
    public function onToggle($params = []): void
    {
        $this->enabled = (bool) ($params['value'] ?? true);
    }

    #[On('dispatchSettingsChanged')]
    public function refreshBanner(): void
    {
        $this->enabled = WhatsAppDispatchSettings::get()->enabled;
    }

    public function render()
    {
        return view('livewire.dispatch-banner');
    }
}
