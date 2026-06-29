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
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class AppointmentList extends Component
{
    use WithPagination;

    public string $filter_nombre = '';

    public string $filter_apellidos = '';

    public bool $show_filters_nombre = true;

    public bool $filter_enviado = false;

    public bool $filter_activo = false;

    public bool $filter_entregado = false;

    public bool $showAllHistory = false;

    public bool $sentOnly = false;

    public bool $showAppointmentNavigation = false;

    public string $dateFilter = 'upcoming';

    public string $sort_by = 'fecha';

    public string $sort_direction = 'asc';

    public ?int $clientId = null;

    public ?int $appointmentPendingDeletionId = null;

    /** @var array<int, int|string> */
    public array $selectedAppointmentIds = [];

    public bool $bulkDeleteConfirmationOpen = false;

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
        $this->showAppointmentNavigation = (request()->routeIs('appointments.index') || request()->routeIs('appointments.sent'))
          && request()->query() === [];

        $clientId = request()->integer('client');

        if ($clientId > 0 && Client::query()->whereKey($clientId)->exists()) {
            $this->clientId = $clientId;
        }

        if (request()->query('client')) {
            $this->show_filters_nombre = false;
        }

        if (request()->routeIs('appointments.sent')) {
            $this->sentOnly = true;
            $this->filter_enviado = true;
            $this->filter_activo = false;
            $this->filter_entregado = false;
        }

        $this->deliveryStatusesSyncedAt = Cache::get('appointment_delivery_statuses_synced_at');
    }

    public function updated(string $property): void
    {
        if (in_array($property, ['filter_nombre', 'filter_apellidos'], true)) {
            $this->resetAppointmentsPage();

            return;
        }

        if ($property === 'dateFilter') {
            if (! in_array($this->dateFilter, ['upcoming', 'all', 'past'], true)) {
                $this->dateFilter = 'upcoming';
            }

            $this->resetAppointmentsPage();

            return;
        }

        if (in_array($property, ['filter_enviado', 'filter_activo', 'filter_entregado'], true)) {
            $this->forceDeliveryStatusSync();
            $this->syncExclusiveDeliveryFilters($property);
            $this->resetAppointmentsPage();

            return;
        }

        if ($property === 'showAllHistory') {
            if ($this->showAllHistory) {
                $this->filter_enviado = false;
                $this->filter_entregado = false;
                $this->filter_activo = false;
                $this->dateFilter = 'all';
            }

            $this->resetAppointmentsPage();

            return;
        }

        if ($property === 'sort_by') {
            if (! in_array($this->sort_by, ['cliente', 'fecha'], true)) {
                $this->sort_by = 'fecha';
            }

            $this->resetAppointmentsPage();

            return;
        }

        if ($property === 'sort_direction') {
            if (! in_array($this->sort_direction, ['asc', 'desc'], true)) {
                $this->sort_direction = 'asc';
            }

            $this->resetAppointmentsPage();
        }
    }

    private function forceDeliveryStatusSync(): int
    {
        $updated = $this->deliveryStatusSyncer->syncAll($this->clientId, force: true);
        $this->deliveryStatusesSyncedAt = now(config('app.timezone'))->format('H:i - d/m/Y');
        Cache::forever('appointment_delivery_statuses_synced_at', $this->deliveryStatusesSyncedAt);

        return $updated;
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
    }

    public function confirmDelete(int $appointmentId): void
    {
        $this->appointmentPendingDeletionId = $appointmentId;
    }

    public function cancelDelete(): void
    {
        $this->appointmentPendingDeletionId = null;
    }

    public function toggleVisibleAppointments(array $appointmentIds): void
    {
        $appointmentIds = array_values(array_unique(array_map('intval', $appointmentIds)));
        $selectedIds = array_values(array_unique(array_map('intval', $this->selectedAppointmentIds)));

        $this->selectedAppointmentIds = array_diff($appointmentIds, $selectedIds) === []
          ? array_values(array_diff($selectedIds, $appointmentIds))
          : array_values(array_unique([...$selectedIds, ...$appointmentIds]));
    }

    public function confirmBulkDelete(): void
    {
        $this->bulkDeleteConfirmationOpen = $this->selectedAppointmentIds !== [];
    }

    public function deleteSelected(): void
    {
        if (! $this->clientId || $this->selectedAppointmentIds === []) {
            return;
        }

        $deleted = Appointment::query()
            ->where('client_id', $this->clientId)
            ->whereKey(array_map('intval', $this->selectedAppointmentIds))
            ->delete();

        $this->selectedAppointmentIds = [];
        $this->bulkDeleteConfirmationOpen = false;

        session()->flash('status', sprintf('%d cita(s) eliminada(s) correctamente.', $deleted));

        $this->redirect(route('appointments.index', ['client' => $this->clientId]));
    }

    public function deleteConfirmed(): void
    {
        if (! $this->appointmentPendingDeletionId) {
            return;
        }

        Appointment::query()->whereKey($this->appointmentPendingDeletionId)->delete();
        $this->appointmentPendingDeletionId = null;

        session()->flash('status', 'Cita eliminada correctamente.');

        $this->redirect(url()->previous());
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
            $this->redirect(url()->previous());

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

        $this->redirect(url()->previous());
    }

    public function sendNow(int $appointmentId, WhatsAppSender $sender): void
    {
        $appointment = Appointment::query()
            ->with('client')
            ->findOrFail($appointmentId);

        if ($appointment->enviado) {
            session()->flash('status', 'Esta cita ya tiene el WhatsApp enviado.');
            $this->redirect(url()->previous());

            return;
        }

        if (! $appointment->isFuture()) {
            session()->flash('status', 'Las citas pasadas no pueden enviarse.');
            $this->redirect(url()->previous());

            return;
        }

        if (! $appointment->activo) {
            session()->flash('status', 'Las citas no pendientes no pueden enviarse.');
            $this->redirect(url()->previous());

            return;
        }

        $client = $appointment->client;

        if (! $client) {
            session()->flash('status', 'No se pudo enviar el WhatsApp porque la cita no tiene cliente asociado.');
            $this->redirect(url()->previous());

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

        $this->redirect(url()->previous());
    }

    public function syncDeliveryStatuses(): void
    {
        $updated = $this->forceDeliveryStatusSync();

        if ($updated > 0) {
            session()->flash('status', $updated === 1 ? 'Se ha actualizado 1 cita' : 'Se han actualizado '.$updated.' citas');
            $this->redirect(url()->previous());

            return;
        }

        session()->flash('status', 'Todos los registros de citas y demás datos están actualizados.');

        $this->redirect(url()->previous());
    }

    public function render()
    {
        $selectedClient = $this->selectedClient();
        $now = Carbon::now(config('app.timezone'));
        $appointmentsQuery = $this->appointmentsQuery($selectedClient, $now);

        $appointmentsCount = (clone $appointmentsQuery)->count();
        $appointments = $this->resolveAppointments($appointmentsQuery, $selectedClient, $now);

        $showSentColumns = $this->showAllHistory || $appointments->getCollection()->contains(fn (Appointment $appointment): bool => $appointment->enviado);
        $showDeliveredColumns = $this->showAllHistory || $appointments->getCollection()->contains(fn (Appointment $appointment): bool => $appointment->entregado || filled($appointment->latestWhatsAppMessage?->provider_message_id));
        $showReadColumn = $this->showAllHistory || $appointments->getCollection()->contains(fn (Appointment $appointment): bool => filled($appointment->whatsapp_read_at));
        $showPendingColumn = $this->showAllHistory || $appointments->getCollection()->contains(fn (Appointment $appointment): bool => ! $appointment->enviado);
        $showBulkActions = $selectedClient && ! $this->sentOnly;
        $visibleAppointmentIds = $appointments->getCollection()->pluck('id')->all();
        $allVisibleAppointmentsSelected = $visibleAppointmentIds !== []
          && array_diff($visibleAppointmentIds, array_map('intval', $this->selectedAppointmentIds)) === [];

        $appointmentPendingDeletion = $this->appointmentPendingDeletionId
          ? Appointment::query()->with('client')->find($this->appointmentPendingDeletionId)
          : null;

        return view('livewire.appointment-list', [
            'appointments' => $appointments,
            'appointmentsCount' => $appointmentsCount,
            'appointmentPendingDeletion' => $appointmentPendingDeletion,
            'selectedClient' => $selectedClient,
            'sentOnly' => $this->sentOnly,
            'showSentColumns' => $showSentColumns,
            'showDeliveredColumns' => $showDeliveredColumns,
            'showReadColumn' => $showReadColumn,
            'showPendingColumn' => $showPendingColumn,
            'showBulkActions' => $showBulkActions,
            'visibleAppointmentIds' => $visibleAppointmentIds,
            'allVisibleAppointmentsSelected' => $allVisibleAppointmentsSelected,
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

    private function resetAppointmentsPage(): void
    {
        $this->resetPage('appointmentsPage');
    }

    private function syncExclusiveDeliveryFilters(string $activeFilter): void
    {
        if ($activeFilter === 'filter_enviado' && $this->filter_enviado) {
            $this->filter_activo = false;
            $this->filter_entregado = false;
        }

        if ($activeFilter === 'filter_activo' && $this->filter_activo) {
            $this->filter_enviado = false;
            $this->filter_entregado = false;
        }

        if ($activeFilter === 'filter_entregado' && $this->filter_entregado) {
            $this->filter_enviado = false;
            $this->filter_activo = false;
        }
    }

    private function selectedClient(): ?Client
    {
        return $this->clientId ? Client::query()->find($this->clientId) : null;
    }

    private function appointmentsQuery(?Client $selectedClient, Carbon $now): Builder
    {
        return Appointment::query()
            ->select('appointments.*')
            ->with(['client', 'latestWhatsAppMessage'])
            ->leftJoin('clients', 'clients.id', '=', 'appointments.client_id')
            ->when($selectedClient, fn (Builder $query) => $query->where('appointments.client_id', $selectedClient->id))
            ->when($this->filter_nombre, fn (Builder $query) => $query->whereHas('client', fn ($clientQuery) => $clientQuery->where('nombre', 'like', '%'.$this->filter_nombre.'%')))
            ->when($this->filter_apellidos, fn (Builder $query) => $query->whereHas('client', fn ($clientQuery) => $clientQuery->where('apellidos', 'like', '%'.$this->filter_apellidos.'%')))
            ->when($this->sentOnly, fn (Builder $query) => $query->where('appointments.enviado', true))
            ->when(! $this->showAllHistory && $selectedClient && ! $this->sentOnly && $this->dateFilter === 'upcoming', fn (Builder $query) => $this->whereFutureAppointment($query, $now))
            ->when(! $this->showAllHistory && $selectedClient && ! $this->sentOnly && $this->dateFilter === 'past', fn (Builder $query) => $this->wherePastAppointment($query, $now))
            ->when(! $this->showAllHistory && ! $this->sentOnly && $this->filter_entregado, fn (Builder $query) => $query->where('appointments.entregado', true))
            ->when(! $this->showAllHistory && ! $this->sentOnly && $this->filter_enviado, fn (Builder $query) => $query->where('appointments.enviado', true))
            ->when(! $this->showAllHistory && ! $this->sentOnly && ! $this->filter_entregado && ! $this->filter_enviado && $this->filter_activo, function (Builder $query) use ($now): void {
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
            ->when(! $this->showAllHistory && ! $selectedClient && ! $this->sentOnly && ! $this->filter_entregado && ! $this->filter_enviado && ! $this->filter_activo, function (Builder $query) use ($now): void {
                $query
                    ->where('appointments.enviado', false)
                    ->where('appointments.activo', true);

                $this->whereFutureAppointment($query, $now);
            })
            ->when($this->sort_by === 'cliente', function (Builder $query): void {
                $query
                    ->orderBy('clients.nombre', $this->sort_direction)
                    ->orderBy('clients.apellidos', $this->sort_direction)
                    ->orderBy('appointments.fecha', $this->sort_direction)
                    ->orderBy('appointments.hora', $this->sort_direction);
            }, function (Builder $query): void {
                $query
                    ->orderBy('appointments.fecha', $this->sort_direction)
                    ->orderBy('appointments.hora', $this->sort_direction);
            });
    }

    private function resolveAppointments(Builder $appointmentsQuery, ?Client $selectedClient, Carbon $now): LengthAwarePaginator
    {
        if ($selectedClient || $this->sentOnly) {
            return $appointmentsQuery->paginate(15, ['appointments.*'], 'appointmentsPage');
        }

        return $this->paginateUniqueAppointments(
            $this->sortUniqueAppointments(
                $this->buildUniqueAppointments($appointmentsQuery->get(), $now)
            )
        );
    }

    /**
     * @param  Collection<int, Appointment>  $appointments
     */
    private function paginateUniqueAppointments(Collection $appointments): LengthAwarePaginator
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage('appointmentsPage');
        $perPage = 15;
        $items = $appointments->forPage($currentPage, $perPage)->values();

        return new LengthAwarePaginator(
            $items,
            $appointments->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'appointmentsPage',
            ],
        );
    }

    /**
     * @param  Collection<int, Appointment>  $appointments
     * @return Collection<int, Appointment>
     */
    private function sortUniqueAppointments(Collection $appointments): Collection
    {
        return $appointments
            ->sort(function (Appointment $left, Appointment $right): int {
                $comparison = $this->sort_by === 'cliente'
                  ? $this->compareByClient($left, $right)
                  : $this->compareBySchedule($left, $right);

                return $this->sort_direction === 'desc' ? -$comparison : $comparison;
            })
            ->values();
    }

    private function compareByClient(Appointment $left, Appointment $right): int
    {
        $leftName = mb_strtolower($left->client?->full_name ?? '');
        $rightName = mb_strtolower($right->client?->full_name ?? '');

        $comparison = $leftName <=> $rightName;

        if ($comparison !== 0) {
            return $comparison;
        }

        return $left->scheduledFor()->getTimestamp() <=> $right->scheduledFor()->getTimestamp();
    }

    private function compareBySchedule(Appointment $left, Appointment $right): int
    {
        $leftTimestamp = $left->scheduledFor()->getTimestamp();
        $rightTimestamp = $right->scheduledFor()->getTimestamp();

        $comparison = $leftTimestamp <=> $rightTimestamp;

        if ($comparison !== 0) {
            return $comparison;
        }

        return mb_strtolower($left->client?->full_name ?? '') <=> mb_strtolower($right->client?->full_name ?? '');
    }

    /**
     * @param  Collection<int, Appointment>  $appointments
     * @return Collection<int, Appointment>
     */
    private function buildUniqueAppointments(Collection $appointments, Carbon $now): Collection
    {
        return $appointments
            ->groupBy('client_id')
            ->map(function (Collection $clientAppointments) use ($now): Appointment {
                $appointment = $clientAppointments
                    ->sortBy(fn (Appointment $appointment): array => [
                        abs($appointment->scheduledFor()->getTimestamp() - $now->getTimestamp()),
                        $appointment->scheduledFor()->getTimestamp(),
                    ])
                    ->firstOrFail();

                $appointment->setAttribute('appointments_count', $clientAppointments->count());

                return $appointment;
            })
            ->values();
    }
}
