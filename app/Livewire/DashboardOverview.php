<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;

class DashboardOverview extends Component
{
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
        ]);
    }

    private function targetDate(): Carbon
    {
        $now = now(config('app.timezone'));

        if ($now->isSaturday()) {
            return $now->next(Carbon::MONDAY);
        }

        return $now->addDay();
    }
}
