<?php

namespace Tests\Feature;

use App\Livewire\Settings\AppointmentCleanupSettings;
use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AppointmentCleanupSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cleanup_settings_are_saved_when_selection_changes(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Livewire::actingAs($admin)
            ->test(AppointmentCleanupSettings::class)
            ->assertSet('retentionPeriod', 'disabled')
            ->call('persistRetentionPeriod', '3_months')
            ->assertSet('status', 'Se guardarán las citas con un máximo de 3 meses de antiguedad');

        $this->assertSame('3_months', AppSetting::get()->retention_period);
    }

    public function test_settings_page_shows_cleanup_section(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('settings.index'))
            ->assertOk()
            ->assertSee('Mantenimiento / Opciones')
            ->assertSee('Desactivar')
            ->assertSee('2 años')
            ->assertSee('5 años');
    }
}
