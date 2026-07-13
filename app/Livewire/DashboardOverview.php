<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\WhatsAppMessage;
use Illuminate\View\View;
use Livewire\Component;

class DashboardOverview extends Component
{
    public function render(): View
    {
        $now = now(config('app.timezone'));
        $weekStart = $now->copy()->subDays(6)->startOfDay();

        $upcoming = Appointment::query()->active()->upcoming();

        $nextAppointments = (clone $upcoming)
            ->select(['id', 'client_id', 'fecha', 'hora', 'enviado', 'whatsapp_sent_at'])
            ->with('client:id,nombre,apellidos,telefono')
            ->orderBy('fecha')
            ->orderBy('hora')
            ->limit(5)
            ->get();

        return view('livewire.dashboard-overview', [
            'now' => $now,
            'todayCount' => Appointment::query()
                ->active()
                ->whereDate('fecha', $now->toDateString())
                ->count(),
            'nextAppointment' => $nextAppointments->first(),
            'upcomingWithoutReminderCount' => (clone $upcoming)
                ->whereNull('whatsapp_sent_at')
                ->count(),
            'rescheduleCount' => Appointment::query()
                ->active()
                ->where('pendiente_reprogramacion', true)
                ->count(),
            'failedCount' => WhatsAppMessage::query()
                ->outbound()
                ->where('status', WhatsAppMessage::STATUS_FAILED)
                ->count(),
            'nextAppointments' => $nextAppointments,
            'sentLastSevenDays' => WhatsAppMessage::query()
                ->outbound()
                ->where('status', WhatsAppMessage::STATUS_SENT)
                ->where('created_at', '>=', $weekStart)
                ->count(),
            'failedLastSevenDays' => WhatsAppMessage::query()
                ->outbound()
                ->where('status', WhatsAppMessage::STATUS_FAILED)
                ->where('created_at', '>=', $weekStart)
                ->count(),
            'cancelledCount' => Appointment::query()->where('activo', false)->count(),
            'totalCount' => Appointment::query()->count(),
        ])->extends('layouts.app')->section('content');
    }
}
