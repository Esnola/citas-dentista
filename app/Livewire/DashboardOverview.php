<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;

class DashboardOverview extends Component
{
    public int $selectedDateOffset = 0;

    public function selectDate(int $offset): void
    {
        $this->selectedDateOffset = $offset;
    }

    public function render(): View
    {
        Carbon::setLocale('es');
        $targetDate = $this->targetDate();

        return view('livewire.dashboard-overview', [
            'pendingCount' => WhatsAppMessage::pending()->count(),
            'sentCount' => WhatsAppMessage::where('status', WhatsAppMessage::STATUS_SENT)->count(),
            'failedCount' => WhatsAppMessage::where('status', WhatsAppMessage::STATUS_FAILED)->count(),
            'nextAppointments' => Appointment::query()
                ->with('client')
                ->where('activo', true)
                ->whereDate('fecha', $targetDate->toDateString())
                ->orderBy('hora')
                ->limit(5)
                ->get(),
            'targetDates' => $this->targetDates(),
            'selectedDate' => $targetDate,
            'sundayWarning' => $this->sundayWarning(),
        ]);
    }

    private function targetDate(): Carbon
    {
        return $this->resolvedDates()[$this->selectedDateOffset];
    }

    /**
     * @return array<int, Carbon>
     */
    private function resolvedDates(): array
    {
        $now = now(config('app.timezone'));
        $dates = [0 => $now->copy()->startOfDay()];

        for ($offset = 1; $offset <= 3; $offset++) {
            $date = $now->copy()->addDays($offset);

            if ($date->isSunday()) {
                $date->addDay();
            }

            if (isset($dates[$offset - 1]) && $date->toDateString() === $dates[$offset - 1]->toDateString()) {
                $date->addDay();
                if ($date->isSunday()) {
                    $date->addDay();
                }
            }

            $dates[$offset] = $date;
        }

        return $dates;
    }

    /**
     * @return array{offset:int,label:string,date:Carbon}
     */
    private function targetDates(): array
    {
        $resolved = $this->resolvedDates();

        return collect([0, 1, 2, 3])
            ->mapWithKeys(fn (int $offset) => [
                $offset => [
                    'offset' => $offset,
                    'label' => match ($offset) {
                        0 => 'Hoy',
                        1 => 'Mañana',
                        2 => 'Pasado mañana',
                        3 => 'En 3 días',
                    },
                    'date' => $resolved[$offset],
                ],
            ])
            ->all();
    }

    private function sundayWarning(): ?string
    {
        if ($this->selectedDateOffset === 0) {
            return null;
        }

        $now = now(config('app.timezone'));
        $rawDate = $now->copy()->addDays($this->selectedDateOffset);

        if (! $rawDate->isSunday()) {
            return null;
        }

        $resolvedDate = $this->targetDate();

        return 'La fecha seleccionada cae en domingo, se mostrarán las citas del '.$resolvedDate->translatedFormat('l d \\d\\e F');
    }
}
