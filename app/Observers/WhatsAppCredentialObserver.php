<?php

namespace App\Observers;

use App\Models\WhatsAppCredential;
use Illuminate\Support\Facades\Artisan;

class WhatsAppCredentialObserver
{
    public function saved(WhatsAppCredential $credential): void
    {
        Artisan::call('view:clear');
    }

    public function deleted(WhatsAppCredential $credential): void
    {
        Artisan::call('view:clear');
    }
}
