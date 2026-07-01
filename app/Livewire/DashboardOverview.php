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

    public function selectDate(string $offset): void
    {
        $this->selectedDateOffset = (int) $offset;
    }

    public function render(): View
    {
        Carbon::setLocale('es');
        $targetDate = $this->targetDate();

        return view('livewire.dashboard-overview', [
            'totales' => Appointment::count(),
            'pendingCount' => Appointment::with('client')
                ->where('activo', true)
                ->where('whatsapp_sent_at', null)
                ->where('fecha', '>', now())
                ->count(),
            'caducados' => Appointment::with('client')
                ->where('activo', true)
                ->where('whatsapp_sent_at', null)
                ->where('entregado', false)
                ->where('fecha', '<', now())->count(),
            'cancelados' => Appointment::where('activo', false)
                ->where('whatsapp_sent_at', null)
                ->where('entregado', false)
                ->count(),
            'sentCount' => WhatsAppMessage::where('status', WhatsAppMessage::STATUS_SENT)->count(),
            'failedCount' => WhatsAppMessage::where('status', WhatsAppMessage::STATUS_FAILED)->count(),
            'nextAppointments' => Appointment::query()
                ->with('client')
                ->whereDate('fecha', $targetDate->toDateString())
                ->orderBy('hora')
                ->get()
                ->groupBy(fn (Appointment $appointment) => $appointment->hora),
            'targetDates' => $this->targetDates(),
            'selectedDate' => $targetDate,
            'sundayWarning' => $this->sundayWarning(),
            'resolvedDates' => $this->resolvedDates(),
            'futureDayOptions' => $this->futureDayOptions(),
        ]);
    }

    private function targetDate(): Carbon
    {
        return $this->resolvedDates()[$this->selectedDateOffset];
    }

    private function resolvedDates(): array
    {
        $now = now(config('app.timezone'));
        $dates = [0 => $now->copy()->startOfDay()];

        for ($offset = 1; $offset <= 10; $offset++) {
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

    private function targetDates(): array
    {
        $resolved = $this->resolvedDates();

        return collect([0, 1])
            ->mapWithKeys(fn (int $offset) => [
                $offset => [
                    'offset' => $offset,
                    'label' => match ($offset) {
                        0 => 'Hoy',
                        1 => 'Mañana',
                        default => '',
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

        return 'La fecha seleccionada es domingo, mostrando las citas del '.$resolvedDate->translatedFormat('l d');
    }

    public function futureDayOptions(): array
    {
        return range(2, 10);
    }

    /**
     * @return array<int, array{label: string, classes: string}>
     */
    public function appointmentIncidences(Appointment $appointment): array
    {
        $incidences = [];

        if (! $appointment->activo) {
            $incidences[] = [
                'label' => 'Desactivada',
                'classes' => 'border-red-500/20 bg-red-500/10 text-red-300',
            ];
        }

        if (! $appointment->enviado) {
            $incidences[] = [
                'label' => 'Sin enviar',
                'classes' => 'border-amber-500/20 bg-amber-500/10 text-amber-300',
            ];
        } elseif (! $appointment->entregado) {
            $incidences[] = [
                'label' => 'No entregada',
                'classes' => 'border-orange-500/20 bg-orange-500/10 text-orange-300',
            ];
        } elseif ($appointment->whatsapp_read_at) {
            $incidences[] = [
                'label' => 'Leída',
              'icono'=>true,
                'classes' => 'border-green-500/20 bg-green-500/10 text-green-300',
            ];
        }

        return $incidences;
    }
}
