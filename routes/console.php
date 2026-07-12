<?php

use App\Console\Commands\DispatchDueWhatsAppMessages;
use App\Console\Commands\PurgePastAppointments;
use App\Console\Commands\SyncWhatsAppDeliveryStatus;
use App\Models\AppSetting;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(DispatchDueWhatsAppMessages::class)
    ->everyMinute()
    ->withoutOverlapping()
    ->when(function (): bool {
        return AppSetting::get()->dispatch_enabled;
    });

Schedule::command(SyncWhatsAppDeliveryStatus::class)->everyMinute()->withoutOverlapping();

Schedule::command(PurgePastAppointments::class)
    ->daily()
    ->withoutOverlapping()
    ->when(function (): bool {
        return AppSetting::get()->isEnabled();
    });
