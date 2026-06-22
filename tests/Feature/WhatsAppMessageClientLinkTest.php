<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsAppMessageClientLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_messages_list_shows_link_to_client_record(): void
    {
        Carbon::setTestNow('2026-06-22 15:30:00');

        $admin = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        WhatsAppMessage::query()->create([
            'user_id' => $admin->id,
            'client_id' => $client->id,
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
            'scheduled_for' => now()->addDay(),
            'message' => 'Hola Ana',
            'source' => WhatsAppMessage::SOURCE_MANUAL,
            'status' => WhatsAppMessage::STATUS_PENDING,
        ]);

        $this->actingAs($admin)
            ->get(route('messages.index'))
            ->assertOk()
            ->assertSee(route('clients.index', ['client' => $client->id]), false)
            ->assertSee('Ana Pérez');

        Carbon::setTestNow();
    }
}
