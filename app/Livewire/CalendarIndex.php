<?php

namespace App\Livewire;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class CalendarIndex extends Component
{
    public string $month;

    public function mount(): void
    {
        $this->month = now(config('app.timezone'))->startOfMonth()->toDateString();
    }

    public function previousMonth(): void
    {
        $this->month = $this->selectedMonth()->subMonthNoOverflow()->toDateString();
    }

    public function nextMonth(): void
    {
        $this->month = $this->selectedMonth()->addMonthNoOverflow()->toDateString();
    }

    public function currentMonth(): void
    {
        $this->month = now(config('app.timezone'))->startOfMonth()->toDateString();
    }

    public function render(): View
    {
        Carbon::setLocale('es');

        $selectedMonth = $this->selectedMonth();

        return view('livewire.calendar-index', [
            'selectedMonth' => $selectedMonth,
            'calendarWeeks' => $this->calendarWeeks($selectedMonth),
        ]);
    }

    private function selectedMonth(): Carbon
    {
        return Carbon::parse($this->month, config('app.timezone'))->startOfMonth();
    }

    private function calendarWeeks(Carbon $selectedMonth): Collection
    {
        $monthStart = $selectedMonth->copy()->startOfMonth();
        $monthEnd = $selectedMonth->copy()->endOfMonth();

        $appointmentStats = Appointment::query()
            ->whereDate('fecha', '>=', $monthStart->toDateString())
            ->whereDate('fecha', '<=', $monthEnd->toDateString())
            ->get(['id', 'fecha', 'cita_activa'])
            ->groupBy(fn (Appointment $appointment): string => $appointment->fecha->toDateString())
            ->map(fn (Collection $appointments): array => [
                'total' => $appointments->count(),
                'inactive' => $appointments->where('cita_activa', false)->count(),
            ]);

        $days = collect();
        $firstCalendarDay = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
        $lastCalendarDay = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);

        for ($date = $firstCalendarDay; $date->lte($lastCalendarDay); $date->addDay()) {
            $dateKey = $date->toDateString();
            $isCurrentMonth = $date->isSameMonth($selectedMonth);
            $isSunday = $date->isSunday();

            $days->push([
                'date' => $date->copy(),
                'is_current_month' => $isCurrentMonth,
                'is_sunday' => $isSunday,
                'appointments_count' => $isCurrentMonth && ! $isSunday ? (int) data_get($appointmentStats, $dateKey.'.total', 0) : 0,
                'inactive_appointments_count' => $isCurrentMonth && ! $isSunday ? (int) data_get($appointmentStats, $dateKey.'.inactive', 0) : 0,
            ]);
        }

        return $days->chunk(7);
    }
}
