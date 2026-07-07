<?php

namespace App\Livewire;

use App\Models\AppointmentReminderPreference;
use App\Models\WhatsAppDispatchSettings;
use Livewire\Component;

class AppointmentReminderSettings extends Component
{
    /**
     * @var list<int>
     */
    public array $whatsappLeadDays = [];

    /**
     * @var list<int>
     */
    public array $emailLeadDays = [];

    public bool $dispatchEnabled = true;

    /**
     * @var list<string>
     */
    public array $dispatchHours = [];

    public string $status = '';

    public function mount(): void
    {
        $selections = AppointmentReminderPreference::selections();

        $this->whatsappLeadDays = $selections[AppointmentReminderPreference::CHANNEL_WHATSAPP] ?? [];
        $this->emailLeadDays = $selections[AppointmentReminderPreference::CHANNEL_EMAIL] ?? [];

        $dispatchSettings = WhatsAppDispatchSettings::get();
        $this->dispatchEnabled = $dispatchSettings->enabled;
        $this->dispatchHours = $dispatchSettings->hours ?? ['09:00', '12:00', '15:00'];
    }

    public function save(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $data = $this->validate();

        AppointmentReminderPreference::saveSelections([
            AppointmentReminderPreference::CHANNEL_WHATSAPP => $data['whatsappLeadDays'],
            AppointmentReminderPreference::CHANNEL_EMAIL => $data['emailLeadDays'],
        ]);

        $dispatchSettings = WhatsAppDispatchSettings::get();
        $dispatchSettings->update([
            'enabled' => $data['dispatchEnabled'],
            'hours' => $data['dispatchHours'],
        ]);

        $this->dispatch('dispatchSettingsChanged');

        $this->whatsappLeadDays = AppointmentReminderPreference::enabledLeadDaysFor(AppointmentReminderPreference::CHANNEL_WHATSAPP);
        $this->emailLeadDays = AppointmentReminderPreference::enabledLeadDaysFor(AppointmentReminderPreference::CHANNEL_EMAIL);
        $this->dispatchEnabled = $dispatchSettings->fresh()->enabled;
        $this->dispatchHours = $dispatchSettings->fresh()->hours;
        $this->status = 'Preferencias de recordatorios guardadas.';
    }

    public function render()
    {
        return view('settings.appointment-reminder-settings', [
            'leadDayOptions' => AppointmentReminderPreference::leadDayOptions(),
            'availableHours' => $this->availableHours(),
        ]);
    }

    protected function rules(): array
    {
        $allowedLeadDays = implode(',', array_keys(AppointmentReminderPreference::leadDayOptions()));
        $allowedHours = implode(',', $this->availableHours());

        return [
            'whatsappLeadDays' => ['array'],
            'whatsappLeadDays.*' => ['integer', 'in:'.$allowedLeadDays],
            'emailLeadDays' => ['array'],
            'emailLeadDays.*' => ['integer', 'in:'.$allowedLeadDays],
            'dispatchEnabled' => ['boolean'],
            'dispatchHours' => ['array'],
            'dispatchHours.*' => ['string', 'in:'.$allowedHours],
        ];
    }

    /**
     * @return list<string>
     */
    private function availableHours(): array
    {
        return array_map(
            fn (int $hour): string => sprintf('%02d:00', $hour),
            range(6, 21),
        );
    }
}
