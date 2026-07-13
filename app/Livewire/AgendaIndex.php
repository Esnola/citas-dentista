<?php

namespace App\Livewire;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class AgendaIndex extends Component
{
    public function render(): View
    {
        Carbon::setLocale('es');
        $selectedMonth = now(config('app.timezone'))->startOfMonth();

        return view('livewire.agenda-index', [
            'selectedMonth' => $selectedMonth,
            'calendarWeeks' => $this->calendarWeeks($selectedMonth),
        ]);
    }

    private function calendarWeeks(Carbon $selectedMonth): Collection
    {
        $monthStart = $selectedMonth->copy()->startOfMonth();
        $monthEnd = $selectedMonth->copy()->endOfMonth();

        $appointmentsByDate = Appointment::query()
            ->with('client')
            ->whereBetween('fecha', [
                $monthStart->toDateString(),
                $monthEnd->toDateString(),
            ])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get()
            ->groupBy(fn (Appointment $appointment): string => $appointment->fecha->toDateString());

        $days = collect();
        $firstCalendarDay = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
        $lastCalendarDay = $monthEnd->copy()->endOfWeek(Carbon::SATURDAY);

        for ($date = $firstCalendarDay; $date->lte($lastCalendarDay); $date->addDay()) {
            if ($date->isSunday()) {
                continue;
            }

            $dateKey = $date->toDateString();
            $isCurrentMonth = $date->isSameMonth($selectedMonth);

            $days->push([
                'date' => $date->copy(),
                'is_current_month' => $isCurrentMonth,
                'is_today' => $date->isToday(),
                'appointments' => $isCurrentMonth ? $appointmentsByDate->get($dateKey, collect()) : collect(),
            ]);
        }

        return $days->chunk(6);
    }
}
