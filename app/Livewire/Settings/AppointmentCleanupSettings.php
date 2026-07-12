<?php

namespace App\Livewire\Settings;

use App\Models\AppSetting;
use Livewire\Component;

class AppointmentCleanupSettings extends Component
{
    public string $retentionPeriod = 'disabled';

    public string $status = '';

    public int $statusNonce = 0;

    public function mount(): void
    {
        $this->retentionPeriod = AppSetting::get()->retention_period;
    }

    public function persistRetentionPeriod(string $retentionPeriod): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $this->retentionPeriod = $retentionPeriod;
        $this->validateOnly('retentionPeriod');

        $settings = AppSetting::get();
        $settings->retention_period = $this->retentionPeriod;
        $settings->save();

        $label = AppSetting::retentionOptions()[$this->retentionPeriod] ?? $this->retentionPeriod;

        $this->retentionPeriod = (string) $settings->retention_period;
        $this->status = $this->retentionPeriod === 'disabled'
            ? 'Borrado automático desactivado.'
            : sprintf('Se guardarán las citas con un máximo de %s de antiguedad', mb_strtolower($label));
        $this->statusNonce++;
    }

    public function render()
    {
        return view('settings.appointment-cleanup-settings', [
            'retentionOptions' => AppSetting::retentionOptions(),
        ]);
    }

    protected function rules(): array
    {
        return [
            'retentionPeriod' => ['required', 'string', 'in:'.implode(',', array_keys(AppSetting::retentionOptions()))],
        ];
    }
}
