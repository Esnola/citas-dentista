<?php

namespace Tests\Feature;

use App\Livewire\ClientForm;
use App\Livewire\ClientList;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ClientManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_clients_screen_filters_and_updates_clients(): void
    {
        Carbon::setTestNow('2026-06-22 10:00:00');

        Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '600123123',
        ]);

        Carbon::setTestNow('2026-06-23 11:15:00');

        Client::query()->create([
            'nombre' => 'Luis',
            'apellidos' => 'Gómez',
            'telefono' => '699999999',
        ]);

        Livewire::test(ClientList::class)
            ->set('filter_nombre', 'Ana')
            ->assertSee('Ana Pérez')
            ->assertDontSee('Luis Gómez');

        Carbon::setTestNow();
    }

    public function test_client_list_searches_after_one_character(): void
    {
        Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '600123123',
        ]);

        $component = Livewire::test(ClientList::class)
            ->assertSee('Las coincidencias aparecerán aquí')
            ->assertDontSee('Ana Pérez');

        $this->assertFalse($component->instance()->getHasClientSearchProperty());

        $component->set('filter_nombre', 'A')
            ->assertSee('Ana Pérez')
            ->assertSee('600123123')
            ->assertSee('WhatsApp');
        $this->assertTrue($component->instance()->getHasClientSearchProperty());
    }

    public function test_client_list_orders_by_name(): void
    {
        $marta = Client::query()->create([
            'nombre' => 'Marta',
            'apellidos' => 'Gómez',
            'telefono' => '699999999',
        ]);
        $ana = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '600123123',
        ]);

        Livewire::test(ClientList::class)
            ->set('filter_nombre', 'a')
            ->assertSeeHtmlInOrder([
                'wire:key="client-'.$ana->id.'"',
                'wire:key="client-'.$marta->id.'"',
            ])
            ->call('sortByName')
            ->assertSeeHtmlInOrder([
                'wire:key="client-'.$marta->id.'"',
                'wire:key="client-'.$ana->id.'"',
            ]);
    }

    public function test_clients_screen_can_edit_selected_client(): void
    {
        Carbon::setTestNow('2026-06-22 10:00:00');

        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '600123123',
        ]);

        Livewire::test(ClientForm::class, ['client' => $client->id])
            ->set('nombre', 'Ana Maria')
            ->set('apellidos', 'Pérez López')
            ->set('telefono', '611222333')
            ->call('save')
            ->assertSee('Cliente actualizado correctamente.');

        $client->refresh();

        $this->assertSame('Ana Maria', $client->nombre);
        $this->assertSame('Pérez López', $client->apellidos);
        $this->assertSame('+34611222333', $client->telefono);
        $this->assertSame('2026-06-22', $client->created_at->toDateString());

        Carbon::setTestNow();
    }

    public function test_clients_screen_can_create_client(): void
    {
        Livewire::test(ClientForm::class)
            ->set('nombre', 'Marta')
            ->set('apellidos', 'Soler')
            ->set('telefono', '600111222')
            ->call('save')
            ->assertSee('Cliente creado correctamente.');

        $this->assertDatabaseHas('clients', [
            'nombre' => 'Marta',
            'apellidos' => 'Soler',
            'telefono' => '+34600111222',
        ]);
    }

    public function test_clients_page_can_open_selected_client_from_query_string(): void
    {
        $admin = User::factory()->create();
        Carbon::setTestNow('2026-06-25 12:40:00');
        $client = Client::query()->create([
            'nombre' => 'Lucía',
            'apellidos' => 'Martín',
            'telefono' => '+34666777888',
        ]);

        $this->actingAs($admin)
            ->get(route('clients.edit', $client))
            ->assertOk()
            ->assertSee('Lucía Martín')
            ->assertSee('Editar cliente')
            ->assertSee('25/06/2026 12:40');

        Carbon::setTestNow();
    }

    public function test_selected_client_card_shows_client_appointments(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Lucía',
            'apellidos' => 'Martín',
            'telefono' => '+34666777888',
        ]);

        $laterAppointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-01',
            'hora' => '10:15',
            'enviado' => false,
            'activo' => true,
        ]);
        $earlierAppointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-30',
            'hora' => '09:00',
            'enviado' => false,
            'activo' => true,
        ]);

        Livewire::test(ClientForm::class, ['client' => $client->id])
            ->assertSee('Citas')
            ->assertSee('01/07/2026')
            ->assertSee('10:15')
            ->assertSee('Pendiente')
            ->assertSeeHtmlInOrder([
                'client-form-appointment-'.$earlierAppointment->id,
                'client-form-appointment-'.$laterAppointment->id,
            ]);

        Carbon::setTestNow();
    }

    public function test_selected_client_card_shows_appointment_statuses_and_actions(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Lucía',
            'apellidos' => 'Martín',
            'telefono' => '+34666777888',
        ]);

        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-06-01',
            'hora' => '09:00',
            'enviado' => false,
            'activo' => true,
        ]);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-01',
            'hora' => '09:00',
            'enviado' => true,
            'activo' => true,
        ]);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-02',
            'hora' => '09:00',
            'enviado' => false,
            'activo' => true,
        ]);
        Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-03',
            'hora' => '09:00',
            'enviado' => false,
            'activo' => false,
        ]);

        $component = Livewire::test(ClientForm::class, ['client' => $client->id])
            ->assertSee('No Enviado!')
            ->assertSee('Enviado')
            ->assertSee('Pendiente')
            ->assertSee('Inactivo')
            ->assertSeeHtml('text-red-400')
            ->assertSeeHtml('text-green-400')
            ->assertSeeHtml('text-yellow-400')
            ->assertSeeHtml('text-blue-400');

        $html = $component->html();

        $this->assertSame(2, substr_count($html, 'aria-label="Eliminar cita"'));
        $this->assertSame(2, substr_count($html, 'wire:change="updateAppointmentActiveStatus'));

        Carbon::setTestNow();
    }

    public function test_selected_client_card_can_update_future_unsent_appointment_active_status(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Lucía',
            'apellidos' => 'Martín',
            'telefono' => '+34666777888',
        ]);

        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-01',
            'hora' => '10:15',
            'enviado' => false,
            'activo' => true,
        ]);

        WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'nombre' => 'Lucía',
            'apellidos' => 'Martín',
            'telefono' => '+34666777888',
            'scheduled_for' => now()->addDay(),
            'message' => 'Recordatorio',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_PENDING,
        ]);

        Livewire::test(ClientForm::class, ['client' => $client->id])
            ->call('updateAppointmentActiveStatus', $appointment->id, false)
            ->assertSee('Estado activo actualizado.');

        $this->assertFalse($appointment->refresh()->activo);
        $this->assertSame(0, WhatsAppMessage::query()->where('appointment_id', $appointment->id)->count());

        Carbon::setTestNow();
    }

    public function test_selected_client_card_deletes_locked_appointment(): void
    {
        Carbon::setTestNow('2026-06-23 09:00:00');

        $client = Client::query()->create([
            'nombre' => 'Lucía',
            'apellidos' => 'Martín',
            'telefono' => '+34666777888',
        ]);

        $appointment = Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => '2026-07-01',
            'hora' => '10:15',
            'enviado' => true,
            'activo' => true,
        ]);

        Livewire::test(ClientForm::class, ['client' => $client->id])
            ->assertSeeHtml('aria-label="Eliminar cita"')
            ->call('deleteAppointment', $appointment->id)
            ->assertSee('Cita eliminada correctamente.');

        $this->assertDatabaseMissing('appointments', [
            'id' => $appointment->id,
        ]);

        Carbon::setTestNow();
    }

    public function test_client_list_page_is_separate_from_client_form(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('clients.index'))
            ->assertOk()
            ->assertSee('Clientes registrados')
            ->assertSee('Nuevo cliente')
            ->assertDontSee('Crear cliente');
    }
}
