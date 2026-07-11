<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_page_loads_with_collapsible_sections(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('settings.index'))
            ->assertOk()
            ->assertSee('Ajustes')
            ->assertSee('Credenciales Twilio')
            ->assertSee('Plantillas de Twilio')
            ->assertSee('Mantenimiento / Opciones');
    }
}
