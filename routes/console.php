<?php

use App\Console\Commands\DispatchDueWhatsAppMessages;
use App\Console\Commands\SyncWhatsAppDeliveryStatus;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(DispatchDueWhatsAppMessages::class)->everyMinute()->withoutOverlapping();
Schedule::command(SyncWhatsAppDeliveryStatus::class)->everyMinute()->withoutOverlapping();
