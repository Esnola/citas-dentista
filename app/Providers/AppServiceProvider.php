<?php

namespace App\Providers;

use App\Livewire\DispatchBanner;
use App\Livewire\Settings\AppointmentCleanupSettings;
use App\Livewire\Settings\AppointmentReminderSettings;
use App\Livewire\Settings\DatabaseBackup;
use App\Livewire\Settings\SettingsBackup;
use App\Livewire\Settings\SettingsOverview;
use App\Livewire\Settings\TableBackup;
use App\Livewire\Settings\TwilioContentTemplateSettings;
use App\Livewire\Settings\TwilioCredentialSettings;
use App\Livewire\Settings\WhatsAppConnectionTest;
use App\Livewire\UnreadResponsesNotice;
use App\Models\WhatsAppCredential;
use App\Observers\WhatsAppCredentialObserver;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::component('whatsapp-connection-test', WhatsAppConnectionTest::class);
        Livewire::component('appointment-reminder-settings', AppointmentReminderSettings::class);
        Livewire::component('appointment-cleanup-settings', AppointmentCleanupSettings::class);
        Livewire::component('twilio-content-template-settings', TwilioContentTemplateSettings::class);
        Livewire::component('dispatch-banner', DispatchBanner::class);
        Livewire::component('unread-responses-notice', UnreadResponsesNotice::class);
        Livewire::component('twilio-credential-settings', TwilioCredentialSettings::class);
        Livewire::component('settings-overview', SettingsOverview::class);
        Livewire::component('settings-backup', SettingsBackup::class);
        Livewire::component('database-backup', DatabaseBackup::class);
        Livewire::component('table-backup', TableBackup::class);

        WhatsAppCredential::observe(WhatsAppCredentialObserver::class);
    }
}
