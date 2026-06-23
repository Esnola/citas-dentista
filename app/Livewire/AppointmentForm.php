<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Client;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class AppointmentForm extends Component
{
    use WithPagination;

    public string $filter_nombre = '';

    public string $filter_apellidos = '';

    public string $filter_telefono = '';

    public ?int $selectedClientId = null;

    public ?int $selectedAppointmentId = null;

    public string $fecha = '';

    public string $hora = '';

    public bool $enviado = false;

    public bool $activo = true;

    public function mount(): void
    {
        $appointmentId = (int) request()->route('appointment');

        if ($appointmentId > 0) {
            $this->loadAppointment($appointmentId);

            return;
        }

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

    public function selectClient(int $clientId): void
    {
        if (! $this->canChangeAppointment) {
            session()->flash('status', 'Esta cita no se puede modificar. Solo se puede eliminar.');

            return;
        }

        $client = Client::query()->findOrFail($clientId);

        $this->selectedClientId = $client->id;
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
            $appointment = Appointment::query()->findOrFail($this->selectedAppointmentId);

            if (! $appointment->canBeChanged()) {
                session()->flash('status', 'Esta cita no se puede modificar. Solo se puede eliminar.');

                return;
            }

            $appointment->update($payload);
            session()->flash('status', 'Cita actualizada correctamente.');
        } else {
            $appointment = Appointment::query()->create($payload);
            $this->selectedAppointmentId = $appointment->id;
            session()->flash('status', 'Cita creada correctamente.');
        }
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

    public function getCanChangeAppointmentProperty(): bool
    {
        if (! $this->selectedAppointmentId) {
            return true;
        }

        return (bool) Appointment::query()
            ->whereKey($this->selectedAppointmentId)
            ->first()
            ?->canBeChanged();
    }

    public function getHasClientSearchProperty(): bool
    {
        return max(
            mb_strlen($this->filter_nombre),
            mb_strlen($this->filter_apellidos),
            mb_strlen($this->filter_telefono),
        ) >= 1;
    }

    public function render()
    {
        $clients = $this->hasClientSearch
            ? Client::query()
                ->when($this->filter_nombre, fn ($query) => $query->where('nombre', 'like', '%'.$this->filter_nombre.'%'))
                ->when($this->filter_apellidos, fn ($query) => $query->where('apellidos', 'like', '%'.$this->filter_apellidos.'%'))
                ->when($this->filter_telefono, fn ($query) => $query->where('telefono', 'like', '%'.$this->filter_telefono.'%'))
                ->orderByDesc('created_at')
                ->paginate(8, ['*'], 'clientsPage')
            : new LengthAwarePaginator([], 0, 8, 1, [
                'pageName' => 'clientsPage',
            ]);

        return view('livewire.appointment-form', [
            'clients' => $clients,
            'selectedClient' => $this->selectedClient,
            'selectedAppointment' => $this->selectedAppointment,
            'canChangeAppointment' => $this->canChangeAppointment,
            'hasClientSearch' => $this->hasClientSearch,
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

    private function loadAppointment(int $appointmentId): void
    {
        $appointment = Appointment::query()->with('client')->findOrFail($appointmentId);

        $this->selectedAppointmentId = $appointment->id;
        $this->selectedClientId = $appointment->client_id;
        $this->fecha = $appointment->fecha?->toDateString() ?? '';
        $this->hora = $appointment->hora;
        $this->enviado = (bool) $appointment->enviado;
        $this->activo = (bool) $appointment->activo;
    }
}
