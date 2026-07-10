<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\WhatsAppMessage;
use App\Services\ClientDataDeletionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientDataDeletionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_deleting_a_client_deletes_appointments_and_whatsapp_messages(): void
    {
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = $this->createAppointment($client);

        $appointmentMessage = $this->createWhatsAppMessage($client, $appointment);
        $manualMessage = $this->createWhatsAppMessage($client);

        app(ClientDataDeletionService::class)->deleteClientById($client->id);

        $this->assertModelMissing($client);
        $this->assertModelMissing($appointment);
        $this->assertModelMissing($appointmentMessage);
        $this->assertModelMissing($manualMessage);
    }

    public function test_deleting_an_appointment_deletes_its_whatsapp_messages(): void
    {
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = $this->createAppointment($client);
        $otherAppointment = $this->createAppointment($client, '2026-07-02');

        $message = $this->createWhatsAppMessage($client, $appointment);
        $otherMessage = $this->createWhatsAppMessage($client, $otherAppointment);

        $deleted = app(ClientDataDeletionService::class)->deleteAppointments([$appointment->id], $client->id);

        $this->assertSame(1, $deleted);
        $this->assertModelMissing($appointment);
        $this->assertModelMissing($message);
        $this->assertModelExists($otherAppointment);
        $this->assertModelExists($otherMessage);
    }

    public function test_database_cascades_whatsapp_messages_when_an_appointment_is_deleted(): void
    {
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = $this->createAppointment($client);
        $message = $this->createWhatsAppMessage($client, $appointment);

        $appointment->delete();

        $this->assertModelMissing($message);
    }

    public function test_deleting_an_appointment_deletes_messages_that_reference_its_messages(): void
    {
        $client = Client::query()->create([
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'telefono' => '+34600111222',
        ]);
        $appointment = $this->createAppointment($client);

        $parent = $this->createWhatsAppMessage($client, $appointment);
        $child = $this->createWhatsAppMessage($client, parent: $parent);

        app(ClientDataDeletionService::class)->deleteAppointments([$appointment->id], $client->id);

        $this->assertModelMissing($parent);
        $this->assertModelMissing($child);
    }

    private function createAppointment(Client $client, string $fecha = '2026-07-01'): Appointment
    {
        return Appointment::query()->create([
            'client_id' => $client->id,
            'fecha' => $fecha,
            'hora' => '10:15',
            'enviado' => false,
            'activo' => true,
        ]);
    }

    private function createWhatsAppMessage(Client $client, ?Appointment $appointment = null, ?WhatsAppMessage $parent = null): WhatsAppMessage
    {
        return WhatsAppMessage::query()->create([
            'client_id' => $client->id,
            'appointment_id' => $appointment?->id,
            'parent_id' => $parent?->id,
            'nombre' => $client->nombre,
            'apellidos' => $client->apellidos,
            'telefono' => $client->telefono,
            'scheduled_for' => now()->addDay(),
            'message' => 'Recordatorio',
            'source' => WhatsAppMessage::SOURCE_APPOINTMENT,
            'status' => WhatsAppMessage::STATUS_SENT,
        ]);
    }
}
