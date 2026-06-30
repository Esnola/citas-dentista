<?php

namespace App\Providers;

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
        Livewire::component('whatsapp-connection-test', \App\Livewire\WhatsAppConnectionTest::class);
        Livewire::component('whatsapp-template-manager', \App\Livewire\WhatsAppTemplateManager::class);
        Livewire::component('appointment-reminder-settings', \App\Livewire\AppointmentReminderSettings::class);
    }
}
