<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class AppointmentList extends Component
{
    use WithPagination;

    public string $filter_nombre = '';

    public string $filter_apellidos = '';

    public bool $filter_enviado = false;

    public bool $filter_activo = false;

    public string $sort_by = 'fecha';

    public string $sort_direction = 'asc';

    public ?int $clientId = null;

    public ?int $appointmentPendingDeletionId = null;

    public function mount(): void
    {
        $clientId = request()->integer('client');

        if ($clientId > 0 && Client::query()->whereKey($clientId)->exists()) {
            $this->clientId = $clientId;
        }
    }

    public function updatedFilterNombre(): void
    {
        $this->resetPage('appointmentsPage');
    }

    public function updatedFilterApellidos(): void
    {
        $this->resetPage('appointmentsPage');
    }

    public function updatedFilterEnviado(): void
    {
        if ($this->filter_enviado) {
            $this->filter_activo = false;

            return;
        }

        $this->resetPage('appointmentsPage');
    }

    public function updatedFilterActivo(): void
    {
        if ($this->filter_activo) {
            $this->filter_enviado = false;

            return;
        }

        $this->resetPage('appointmentsPage');
    }

    public function updatedSortBy(): void
    {
        if (! in_array($this->sort_by, ['cliente', 'fecha'], true)) {
            $this->sort_by = 'fecha';
        }

        $this->resetPage('appointmentsPage');
    }

    public function updatedSortDirection(): void
    {
        if (! in_array($this->sort_direction, ['asc', 'desc'], true)) {
            $this->sort_direction = 'asc';
        }

        $this->resetPage('appointmentsPage');
    }

    public function sortByColumn(string $column): void
    {
        if (! in_array($column, ['cliente', 'fecha'], true)) {
            return;
        }

        if ($this->sort_by === $column) {
            $this->sort_direction = $this->sort_direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort_by = $column;
            $this->sort_direction = 'asc';
        }

        $this->resetPage('appointmentsPage');
    }

    public function confirmDelete(int $appointmentId): void
    {
        $this->appointmentPendingDeletionId = $appointmentId;
    }

    public function cancelDelete(): void
    {
        $this->appointmentPendingDeletionId = null;
    }

    public function deleteConfirmed(): void
    {
        if (! $this->appointmentPendingDeletionId) {
            return;
        }

        Appointment::query()->whereKey($this->appointmentPendingDeletionId)->delete();
        $this->appointmentPendingDeletionId = null;

        session()->flash('status', 'Cita eliminada correctamente.');
    }

    public function updateActiveStatus(int $appointmentId, bool|string $activo): void
    {
        if (is_bool($activo)) {
            $isActive = $activo;
        } elseif (in_array($activo, ['0', '1'], true)) {
            $isActive = $activo === '1';
        } else {
            return;
        }

        $appointment = Appointment::query()->findOrFail($appointmentId);

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

    public function render()
    {
        $selectedClient = $this->clientId
            ? Client::query()->find($this->clientId)
            : null;
        $now = Carbon::now(config('app.timezone'));

        $appointmentsQuery = Appointment::query()
            ->select('appointments.*')
            ->with('client')
            ->leftJoin('clients', 'clients.id', '=', 'appointments.client_id')
            ->when($selectedClient, fn ($query) => $query->where('appointments.client_id', $selectedClient->id))
            ->when($this->filter_nombre, fn ($query) => $query->whereHas('client', fn ($clientQuery) => $clientQuery->where('nombre', 'like', '%'.$this->filter_nombre.'%')))
            ->when($this->filter_apellidos, fn ($query) => $query->whereHas('client', fn ($clientQuery) => $clientQuery->where('apellidos', 'like', '%'.$this->filter_apellidos.'%')))
            ->when($this->filter_enviado, fn ($query) => $query->where('appointments.enviado', true))
            ->when($this->filter_activo, function ($query) use ($now): void {
                $query->where('appointments.activo', true)
                    ->where('appointments.enviado', false)
                    ->where(function ($activeQuery) use ($now): void {
                        $activeQuery->whereDate('appointments.fecha', '>', $now->toDateString())
                            ->orWhere(function ($futureAppointmentQuery) use ($now): void {
                                $futureAppointmentQuery->whereDate('appointments.fecha', $now->toDateString())
                                    ->where('appointments.hora', '>', $now->format('H:i:s'));
                            });
                    });
            });

        if ($this->sort_by === 'cliente') {
            $appointmentsQuery
                ->orderBy('clients.nombre', $this->sort_direction)
                ->orderBy('clients.apellidos', $this->sort_direction)
                ->orderBy('appointments.fecha', $this->sort_direction)
                ->orderBy('appointments.hora', $this->sort_direction);
        } else {
            $appointmentsQuery
                ->orderBy('appointments.fecha', $this->sort_direction)
                ->orderBy('appointments.hora', $this->sort_direction);
        }

        $appointments = $appointmentsQuery->paginate(10, ['appointments.*'], 'appointmentsPage');

        $appointmentPendingDeletion = $this->appointmentPendingDeletionId
            ? Appointment::query()->with('client')->find($this->appointmentPendingDeletionId)
            : null;

        return view('livewire.appointment-list', [
            'appointments' => $appointments,
            'appointmentPendingDeletion' => $appointmentPendingDeletion,
            'selectedClient' => $selectedClient,
        ]);
    }
}
