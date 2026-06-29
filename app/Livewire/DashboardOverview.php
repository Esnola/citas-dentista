<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;

class DashboardOverview extends Component
{
    public int $selectedDateOffset = 1;

    public function selectDate(int $offset): void
    {
        $this->selectedDateOffset = $offset;
    }

    public function render(): View
    {
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
        return $this->computeDateSkippingSunday($this->selectedDateOffset);
    }

    /**
     * @return array{offset:int,label:string,date:Carbon}
     */
    private function targetDates(): array
    {
        return collect([1, 2, 3])
            ->mapWithKeys(fn (int $offset) => [
                $offset => [
                    'offset' => $offset,
                    'label' => match ($offset) {
                        1 => 'Mañana',
                        2 => 'Pasado mañana',
                        3 => 'En 3 días',
                    },
                    'date' => $this->computeDateSkippingSunday($offset),
                ],
            ])
            ->all();
    }

    private function computeDateSkippingSunday(int $offset): Carbon
    {
        $now = now(config('app.timezone'));
        $date = $now->copy()->addDays($offset);

        if ($date->isSunday()) {
            $date = $date->addDay();
        }

        return $date;
    }

    private function sundayWarning(): ?string
    {
        $now = now(config('app.timezone'));
        $rawDate = $now->copy()->addDays($this->selectedDateOffset);

        if (! $rawDate->isSunday()) {
            return null;
        }

        $resolvedDate = $this->targetDate();

        return 'La fecha seleccionada cae en domingo, se mostrarán las citas del '.$resolvedDate->translatedFormat('l d \\d\\e F');
    }
}
