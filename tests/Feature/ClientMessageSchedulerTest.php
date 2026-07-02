<?php

namespace Tests\Feature;

use App\Livewire\ClientMessageScheduler;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
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
        $this->assertSame('600123123', $message->telefono);
        $this->assertSame('2026-06-24 11:20:00', $message->scheduled_for->toDateTimeString());

        Carbon::setTestNow();
    }

    public function test_scheduler_rejects_today_and_sundays(): void
    {
        Carbon::setTestNow('2026-06-23 15:30:00');

        $admin = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        $this->actingAs($admin);

        Livewire::test(ClientMessageScheduler::class)
            ->call('selectClient', $client->id)
            ->set('scheduled_date', '2026-06-23')
            ->set('scheduled_time', '11:20')
            ->call('save')
            ->assertHasErrors('scheduled_date');

        Livewire::test(ClientMessageScheduler::class)
            ->call('selectClient', $client->id)
            ->set('scheduled_date', '2026-06-28')
            ->set('scheduled_time', '11:20')
            ->call('save')
            ->assertHasErrors('scheduled_date');

        $this->assertSame(0, WhatsAppMessage::query()->count());

        Carbon::setTestNow();
    }

    public function test_scheduler_default_date_skips_sunday(): void
    {
        Carbon::setTestNow('2026-06-27 15:30:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        Livewire::test(ClientMessageScheduler::class)
            ->call('selectClient', $client->id)
            ->assertSet('scheduled_date', '2026-06-29');

        Carbon::setTestNow();
    }

    public function test_scheduler_can_send_selected_client_message_immediately(): void
    {
        Carbon::setTestNow('2026-06-23 15:30:00');

        $admin = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600123123',
        ]);

        Config::set('whatsapp.driver', 'twilio');
        Config::set('whatsapp.message_mode', 'text');
        Config::set('whatsapp.twilio.account_sid', 'AC123');
        Config::set('whatsapp.twilio.auth_token', 'test-token');
        Config::set('whatsapp.twilio.mode', 'sandbox');
        Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');

        Http::fake([
            'api.twilio.com/*/Messages.json' => Http::response([
                'sid' => 'SMIMMEDIATE123',
                'status' => 'queued',
            ], 201),
        ]);

        $this->actingAs($admin);

        Livewire::test(ClientMessageScheduler::class)
            ->call('selectClient', $client->id)
            ->set('scheduled_date', '2026-06-24')
            ->set('scheduled_time', '10:15')
            ->call('sendNow')
            ->assertSee('WhatsApp enviado ahora y registrado correctamente.');

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://api.twilio.com/2010-04-01/Accounts/AC123/Messages.json'
                && $request['From'] === 'whatsapp:+14155238886'
                && $request['To'] === 'whatsapp:+34600123123'
                && $request['Body'] === 'Hola Ana te recordamos que el día 24/06/2026 tienes una cita a las 10:15 ; saludos Clínica Dental Eugénia';
        });

        $message = WhatsAppMessage::query()->firstOrFail();

        $this->assertSame(WhatsAppMessage::STATUS_SENT, $message->status);
        $this->assertSame($client->id, $message->client_id);
        $this->assertSame('2026-06-24 10:15:00', $message->scheduled_for->toDateTimeString());
        $this->assertSame('SMIMMEDIATE123', $message->provider_message_id);
        $this->assertTrue($message->metadata['immediate_send']);
        $this->assertSame('2026-06-23 15:30:00', $message->metadata['immediate_sent_at']);
        $this->assertNotNull($message->sent_at);

        Carbon::setTestNow();
    }

    public function test_scheduler_can_preselect_client_from_query_string(): void
    {
        Carbon::setTestNow('2026-06-22 15:30:00');

        $admin = User::factory()->create();
        $client = Client::query()->create([
            'nombre' => 'Lucía',
            'apellidos' => 'Martín',
            'telefono' => '+34666777888',
        ]);

        $this->actingAs($admin)
            ->get(route('clients.index', ['client' => $client->id]))
            ->assertOk()
            ->assertSee('Programar desde cliente')
            ->assertSee('Lucía Martín')
            ->assertSee('666777888');

        Carbon::setTestNow();
    }
}
