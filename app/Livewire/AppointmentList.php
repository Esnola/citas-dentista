<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\WhatsAppMessage;
use App\Services\WhatsApp\AppointmentDeliveryStatusSyncer;
use App\Services\WhatsApp\AppointmentImmediateSender;
use App\Services\WhatsApp\WhatsAppSender;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class AppointmentList extends Component
{
    use WithPagination;

    public string $filter_nombre = '';

    public string $filter_apellidos = '';

    public bool $filter_enviado = false;

    public bool $filter_activo = false;

    public bool $filter_entregado = false;

    public string $sort_by = 'fecha';

    public string $sort_direction = 'asc';

    public ?int $clientId = null;

    public ?int $appointmentPendingDeletionId = null;

    public ?string $deliveryStatusesSyncedAt = null;

    private AppointmentImmediateSender $immediateSender;

    private AppointmentDeliveryStatusSyncer $deliveryStatusSyncer;

    public function boot(AppointmentImmediateSender $immediateSender, AppointmentDeliveryStatusSyncer $deliveryStatusSyncer): void
    {
        $this->immediateSender = $immediateSender;
        $this->deliveryStatusSyncer = $deliveryStatusSyncer;
    }

    public function mount(): void
    {
        $clientId = request()->integer('client');

        if ($clientId > 0 && Client::query()->whereKey($clientId)->exists()) {
            $this->clientId = $clientId;
        }

        $this->deliveryStatusesSyncedAt = Cache::get('appointment_delivery_statuses_synced_at');
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
        $this->forceDeliveryStatusSync();

        if ($this->filter_enviado) {
            $this->filter_activo = false;
            $this->filter_entregado = false;
        }

        $this->resetPage('appointmentsPage');
    }

    public function updatedFilterActivo(): void
    {
        $this->forceDeliveryStatusSync();

        if ($this->filter_activo) {
            $this->filter_enviado = false;
            $this->filter_entregado = false;
        }

        $this->resetPage('appointmentsPage');
    }

    public function updatedFilterEntregado(): void
    {
        $this->forceDeliveryStatusSync();

        if ($this->filter_entregado) {
            $this->filter_enviado = false;
            $this->filter_activo = false;
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

        session()->flash('status', 'Estado pendiente actualizado.');
    }

    public function sendNow(int $appointmentId, WhatsAppSender $sender): void
    {
        $appointment = Appointment::query()
            ->with('client')
            ->findOrFail($appointmentId);

        if ($appointment->enviado) {
            session()->flash('status', 'Esta cita ya tiene el WhatsApp enviado.');

            return;
        }

        if (! $appointment->isFuture()) {
            session()->flash('status', 'Las citas pasadas no pueden enviarse.');

            return;
        }

        if (! $appointment->activo) {
            session()->flash('status', 'Las citas no pendientes no pueden enviarse.');

            return;
        }

        $client = $appointment->client;

        if (! $client) {
            session()->flash('status', 'No se pudo enviar el WhatsApp porque la cita no tiene cliente asociado.');

            return;
        }

        $result = $this->immediateSender->send(
            $appointment,
            $client,
            $sender,
            'WhatsApp enviado ahora correctamente.',
            'No se pudo enviar el WhatsApp.'
        );

        session()->flash('status', $result['message']);
    }

    public function syncDeliveryStatuses(): void
    {
        $updated = $this->forceDeliveryStatusSync();

        if ($updated > 0) {
            session()->flash('status', sprintf('Se actualizaron %d cita(s) .', $updated));

            return;
        }

        session()->flash('status', 'Todos los registros de citas y demás datos están actualizados.');
    }

    private function forceDeliveryStatusSync(): int
    {
        $updated = $this->deliveryStatusSyncer->syncAll($this->clientId, force: true);
        $this->deliveryStatusesSyncedAt = now(config('app.timezone'))->format('H:i - d/m/Y');
        Cache::forever('appointment_delivery_statuses_synced_at', $this->deliveryStatusesSyncedAt);

        return $updated;
    }

    public function render()
    {
        $selectedClient = $this->clientId
            ? Client::query()->find($this->clientId)
            : null;
        $now = Carbon::now(config('app.timezone'));

        $appointmentsQuery = Appointment::query()
            ->select('appointments.*')
            ->with(['client', 'latestWhatsAppMessage'])
            ->leftJoin('clients', 'clients.id', '=', 'appointments.client_id')
            ->when($selectedClient, fn ($query) => $query->where('appointments.client_id', $selectedClient->id))
            ->when($this->filter_nombre, fn ($query) => $query->whereHas('client', fn ($clientQuery) => $clientQuery->where('nombre', 'like', '%'.$this->filter_nombre.'%')))
            ->when($this->filter_apellidos, fn ($query) => $query->whereHas('client', fn ($clientQuery) => $clientQuery->where('apellidos', 'like', '%'.$this->filter_apellidos.'%')))
            ->when($this->filter_entregado, fn ($query) => $query->where('appointments.entregado', true))
            ->when($this->filter_enviado, fn ($query) => $query->where('appointments.enviado', true))
            ->when(! $this->filter_entregado && ! $this->filter_enviado && $this->filter_activo, function (Builder $query) use ($now): void {
                $query->where(function (Builder $nonPendingQuery) use ($now): void {
                    $this->wherePastAppointment($nonPendingQuery, $now);
                    $nonPendingQuery->orWhere(function (Builder $inactiveFutureQuery) use ($now): void {
                        $inactiveFutureQuery
                            ->where('appointments.enviado', false)
                            ->where('appointments.activo', false);

                        $this->whereFutureAppointment($inactiveFutureQuery, $now);
                    });
                });
            })
            ->when(! $this->filter_entregado && ! $this->filter_enviado && ! $this->filter_activo, function (Builder $query) use ($now): void {
                $query
                    ->where('appointments.enviado', false)
                    ->where('appointments.activo', true);

                $this->whereFutureAppointment($query, $now);
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

        $appointments = $appointmentsQuery->paginate(15, ['appointments.*'], 'appointmentsPage');

        $showSentColumns = $appointments->getCollection()->contains(fn (Appointment $appointment): bool => $appointment->enviado);
        $showDeliveredColumns = $appointments->getCollection()->contains(fn (Appointment $appointment): bool => $appointment->entregado);
        $showReadColumn = $appointments->getCollection()->contains(fn (Appointment $appointment): bool => filled($appointment->whatsapp_read_at));
        $showPendingColumn = $appointments->getCollection()->contains(fn (Appointment $appointment): bool => ! $appointment->enviado);

        $appointmentPendingDeletion = $this->appointmentPendingDeletionId
            ? Appointment::query()->with('client')->find($this->appointmentPendingDeletionId)
            : null;

        return view('livewire.appointment-list', [
            'appointments' => $appointments,
            'appointmentPendingDeletion' => $appointmentPendingDeletion,
            'selectedClient' => $selectedClient,
            'showSentColumns' => $showSentColumns,
            'showDeliveredColumns' => $showDeliveredColumns,
            'showReadColumn' => $showReadColumn,
            'showPendingColumn' => $showPendingColumn,
        ]);
    }

    private function whereFutureAppointment(Builder $query, Carbon $now): void
    {
        $query->where(function (Builder $futureQuery) use ($now): void {
            $futureQuery
                ->whereDate('appointments.fecha', '>', $now->toDateString())
                ->orWhere(function (Builder $sameDayQuery) use ($now): void {
                    $sameDayQuery
                        ->whereDate('appointments.fecha', $now->toDateString())
                        ->where('appointments.hora', '>', $now->format('H:i:s'));
                });
        });
    }

    private function wherePastAppointment(Builder $query, Carbon $now): void
    {
        $query->where(function (Builder $pastQuery) use ($now): void {
            $pastQuery
                ->whereDate('appointments.fecha', '<', $now->toDateString())
                ->orWhere(function (Builder $sameDayQuery) use ($now): void {
                    $sameDayQuery
                        ->whereDate('appointments.fecha', $now->toDateString())
                        ->where('appointments.hora', '<=', $now->format('H:i:s'));
                });
        });
    }
}
