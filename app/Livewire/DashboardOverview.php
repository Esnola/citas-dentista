<?php

namespace App\Livewire;

use App\Models\WhatsAppMessage;
use Livewire\Component;

class DashboardOverview extends Component
{
    public function render()
    {
        return view('livewire.dashboard-overview', [
            'pendingCount' => WhatsAppMessage::pending()->count(),
            'sentCount' => WhatsAppMessage::where('status', WhatsAppMessage::STATUS_SENT)->count(),
            'failedCount' => WhatsAppMessage::where('status', WhatsAppMessage::STATUS_FAILED)->count(),
            'nextMessages' => WhatsAppMessage::pending()->orderBy('scheduled_for')->limit(5)->get(),
        ]);
    }
}
