<?php

namespace App\Providers;

use App\Livewire\AppointmentReminderSettings;
use App\Livewire\DispatchBanner;
use App\Livewire\TwilioContentTemplateSettings;
use App\Livewire\TwilioCredentialSettings;
use App\Livewire\WhatsAppConnectionTest;
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
        Livewire::component('twilio-content-template-settings', TwilioContentTemplateSettings::class);
        Livewire::component('dispatch-banner', DispatchBanner::class);
        Livewire::component('twilio-credential-settings', TwilioCredentialSettings::class);
    }
}
