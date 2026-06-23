<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\WhatsAppMessage;
use Livewire\Component;

class ClientForm extends Component
{
    public ?int $selectedClientId = null;

    public string $nombre = '';

    public string $apellidos = '';

    public string $telefono = '';

    public function mount(?int $client = null): void
    {
        $clientId = $client ?: (int) request()->route('client');

        if ($clientId > 0) {
            $this->loadClient($clientId);

            return;
        }

        $queryClientId = request()->integer('client');

        if ($queryClientId > 0) {
            $this->loadClient($queryClientId);
        }
    }

    public function save(): void
    {
        $data = $this->validate();

        $payload = [
            'nombre' => $data['nombre'],
            'apellidos' => $data['apellidos'],
            'telefono' => Client::normalizePhone($data['telefono']),
        ];

        if ($this->selectedClientId) {
            Client::query()->whereKey($this->selectedClientId)->update($payload);
            session()->flash('status', 'Cliente actualizado correctamente.');
        } else {
            $client = Client::query()->updateOrCreate(
                ['telefono' => $payload['telefono']],
                $payload
            );
            $this->selectedClientId = $client->id;
            session()->flash('status', $client->wasRecentlyCreated ? 'Cliente creado correctamente.' : 'Cliente actualizado correctamente.');
        }
    }

    public function updateAppointmentActiveStatus(int $appointmentId, bool|string $activo): void
    {
        if (is_bool($activo)) {
            $isActive = $activo;
        } elseif (in_array($activo, ['0', '1'], true)) {
            $isActive = $activo === '1';
        } else {
            return;
        }

        $appointment = Appointment::query()
            ->where('client_id', $this->selectedClientId)
            ->findOrFail($appointmentId);

        if (! $appointment->canBeChanged()) {
            session()->flash('status', 'Esta cita no se puede modificar. Solo se puede eliminar.');

            return;
        }

        $appointment->update([
            'activo' => $isActive,
        ]);

        if (! $isActive) {
            $appointment->whatsAppMessages()
                ->where('status', WhatsAppMessage::STATUS_PENDING)
                ->delete();
        }

        session()->flash('status', 'Estado activo actualizado.');
    }

    public function deleteAppointment(int $appointmentId): void
    {
        Appointment::query()
            ->where('client_id', $this->selectedClientId)
            ->whereKey($appointmentId)
            ->delete();

        session()->flash('status', 'Cita eliminada correctamente.');
    }

    public function getSelectedClientProperty(): ?Client
    {
        return $this->selectedClientId
            ? Client::query()
                ->with(['appointments' => fn ($query) => $query->orderBy('fecha')->orderBy('hora')])
                ->find($this->selectedClientId)
            : null;
    }

    public function render()
    {
        return view('livewire.client-form', [
            'selectedClient' => $this->selectedClient,
        ]);
    }

    protected function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:40'],
        ];
    }

    private function loadClient(int $clientId): void
    {
        $client = Client::query()->findOrFail($clientId);

        $this->selectedClientId = $client->id;
        $this->nombre = $client->nombre;
        $this->apellidos = $client->apellidos;
        $this->telefono = $client->telefono;
    }
}
