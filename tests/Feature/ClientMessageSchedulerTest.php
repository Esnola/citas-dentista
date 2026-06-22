<?php

namespace Tests\Feature;

use App\Livewire\ClientMessageScheduler;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ClientMessageSchedulerTest extends TestCase
{
    use RefreshDatabase;

    public function test_scheduler_creates_message_linked_to_client(): void
    {
        Carbon::setTestNow('2026-06-22 15:30:00');

        $admin = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        $this->actingAs($admin);

        Livewire::test(ClientMessageScheduler::class)
            ->call('selectClient', $client->id)
            ->set('scheduled_date', '2026-06-24')
            ->set('scheduled_time', '11:20')
            ->call('save')
            ->assertSee('Mensaje programado desde la ficha del cliente.');

        $message = WhatsAppMessage::query()->firstOrFail();

        $this->assertSame($client->id, $message->client_id);
        $this->assertSame('Ana', $message->nombre);
        $this->assertSame('Pérez', $message->apellidos);
        $this->assertSame('+34600123123', $message->telefono);
        $this->assertSame('2026-06-24 11:20:00', $message->scheduled_for->toDateTimeString());

        Carbon::setTestNow();
    }
}
