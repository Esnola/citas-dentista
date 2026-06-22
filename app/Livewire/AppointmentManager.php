<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Client;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class AppointmentManager extends Component
{
    use WithPagination;

    public string $filter_nombre = '';
    public string $filter_apellidos = '';
    public string $filter_telefono = '';
    public string $filter_enviado = '';
    public string $filter_activo = '';

    public ?int $selectedClientId = null;
    public ?int $selectedAppointmentId = null;
    public string $fecha = '';
    public string $hora = '';
    public bool $enviado = false;
    public bool $activo = true;

    public function mount(): void
    {
        $clientId = request()->integer('client');

        if ($clientId > 0) {
            $this->selectClient($clientId);
        }
    }

    public function updatedFilterNombre(): void
    {
        $this->resetPage('clientsPage');
    }

    public function updatedFilterApellidos(): void
    {
        $this->resetPage('clientsPage');
    }

    public function updatedFilterTelefono(): void
    {
        $this->resetPage('clientsPage');
    }

    public function updatedFilterEnviado(): void
    {
        $this->resetPage('appointmentsPage');
    }

    public function updatedFilterActivo(): void
    {
        $this->resetPage('appointmentsPage');
    }

    public function selectClient(int $clientId): void
    {
        $client = Client::query()->findOrFail($clientId);

        $this->selectedClientId = $client->id;

        if (! $this->selectedAppointmentId) {
            $this->clearFormState();
        }
    }

    public function selectAppointment(int $appointmentId): void
    {
        $appointment = Appointment::query()->with('client')->findOrFail($appointmentId);

        $this->selectedAppointmentId = $appointment->id;
        $this->selectedClientId = $appointment->client_id;
        $this->fecha = $appointment->fecha?->toDateString() ?? '';
        $this->hora = $appointment->hora;
        $this->enviado = (bool) $appointment->enviado;
        $this->activo = (bool) $appointment->activo;
    }

    public function clearSelection(): void
    {
        $this->selectedAppointmentId = null;
        $this->selectedClientId = null;
        $this->clearFormState();
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        $client = Client::query()->findOrFail($data['selectedClientId']);

        $payload = [
            'client_id' => $client->id,
            'fecha' => $data['fecha'],
            'hora' => $data['hora'],
            'enviado' => (bool) $data['enviado'],
            'activo' => (bool) $data['activo'],
        ];

        if ($this->selectedAppointmentId) {
            Appointment::query()->whereKey($this->selectedAppointmentId)->update($payload);
            $this->statusMessage('Cita actualizada correctamente.');
        } else {
            $appointment = Appointment::query()->create($payload);
            $this->selectedAppointmentId = $appointment->id;
            $this->statusMessage('Cita creada correctamente.');
        }

        $this->resetPage('appointmentsPage');
    }

    public function delete(int $appointmentId): void
    {
        Appointment::query()->whereKey($appointmentId)->delete();

        if ($this->selectedAppointmentId === $appointmentId) {
            $this->clearSelection();
        }

        $this->statusMessage('Cita eliminada correctamente.');
    }

    public function getSelectedClientProperty(): ?Client
    {
        return $this->selectedClientId
            ? Client::query()->find($this->selectedClientId)
            : null;
    }

    public function getSelectedAppointmentProperty(): ?Appointment
    {
        return $this->selectedAppointmentId
            ? Appointment::query()->with('client')->find($this->selectedAppointmentId)
            : null;
    }

    public function render()
    {
        $clients = Client::query()
            ->when($this->filter_nombre, fn ($query) => $query->where('nombre', 'like', '%'.$this->filter_nombre.'%'))
            ->when($this->filter_apellidos, fn ($query) => $query->where('apellidos', 'like', '%'.$this->filter_apellidos.'%'))
            ->when($this->filter_telefono, fn ($query) => $query->where('telefono', 'like', '%'.$this->filter_telefono.'%'))
            ->orderByDesc('created_at')
            ->paginate(8, ['*'], 'clientsPage');

        $appointments = Appointment::query()
            ->with('client')
            ->when($this->filter_enviado !== '', fn ($query) => $query->where('enviado', $this->filter_enviado === '1'))
            ->when($this->filter_activo !== '', fn ($query) => $query->where('activo', $this->filter_activo === '1'))
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->paginate(10, ['*'], 'appointmentsPage');

        return view('livewire.appointment-manager', [
            'clients' => $clients,
            'appointments' => $appointments,
            'selectedClient' => $this->selectedClient,
            'selectedAppointment' => $this->selectedAppointment,
        ]);
    }

    protected function rules(): array
    {
        return [
            'selectedClientId' => [
                'required',
                'integer',
                Rule::exists('clients', 'id'),
            ],
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'enviado' => ['boolean'],
            'activo' => ['boolean'],
        ];
    }

    private function clearFormState(): void
    {
        $this->fecha = '';
        $this->hora = '';
        $this->enviado = false;
        $this->activo = true;
    }

    private function statusMessage(string $message): void
    {
        session()->flash('status', $message);
    }
}
