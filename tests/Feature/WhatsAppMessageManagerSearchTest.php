<?php

namespace Tests\Feature;

use App\Livewire\MessageManager;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WhatsAppMessageManagerSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_filters_search_saved_messages_by_name_and_phone(): void
    {
        $admin = User::factory()->create();

        WhatsAppMessage::query()->create([
            'user_id' => $admin->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '600123123',
            'scheduled_for' => now()->addDay(),
            'message' => 'Mensaje Ana',
            'source' => WhatsAppMessage::SOURCE_MANUAL,
            'status' => WhatsAppMessage::STATUS_PENDING,
        ]);

        WhatsAppMessage::query()->create([
            'user_id' => $admin->id,
            'nombre' => 'Luis',
            'apellidos' => 'Gómez',
            'telefono' => '699999999',
            'scheduled_for' => now()->addDay(),
            'message' => 'Mensaje Luis',
            'source' => WhatsAppMessage::SOURCE_MANUAL,
            'status' => WhatsAppMessage::STATUS_PENDING,
        ]);

        $this->actingAs($admin);

        Livewire::test(MessageManager::class)
            ->set('filter_nombre', 'Ana')
            ->assertSee('Ana')
            ->assertDontSee('Luis');
    }
}
