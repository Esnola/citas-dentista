<?php

namespace App\Livewire;

use App\Models\AppSetting;
use Livewire\Attributes\On;
use Livewire\Component;

class DispatchBanner extends Component
{
    public bool $enabled = true;

    public function mount(): void
    {
        $this->enabled = AppSetting::get()->dispatch_enabled;
    }

    #[On('dispatchToggled')]
    public function onToggle(bool|array $value = true): void
    {
        $this->enabled = (bool) (is_array($value) ? ($value['value'] ?? true) : $value);
    }

    #[On('dispatchSettingsChanged')]
    public function refreshBanner(): void
    {
        $this->enabled = AppSetting::get()->dispatch_enabled;
    }

    public function render()
    {
        return view('livewire.avisos.sin-envio-automatico');
    }
}
