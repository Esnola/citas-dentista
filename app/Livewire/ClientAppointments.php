<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\WhatsAppMessage;
use App\Services\ClientDataDeletionService;
use App\Services\WhatsApp\AppointmentDeliveryStatusSyncer;
use App\Services\WhatsApp\AppointmentImmediateSender;
use App\Services\WhatsApp\WhatsAppSender;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class ClientAppointments extends Component
{
    use WithPagination;

    public string $sort_direction = 'asc';

    public string $filter = 'upcoming';

    public ?string $whatsappFilter = null;

    public int $clientId;

    public ?int $appointmentPendingDeletionId = null;

    public ?int $appointmentPendingResendId = null;

    /** @var array<int, int|string> */
    public array $selectedAppointmentIds = [];

    public bool $selectAll = false;

    public bool $bulkDeleteConfirmationOpen = false;

    public ?string $deliveryStatusesSyncedAt = null;

    public ?Appointment $historyAppointment = null;

    private AppointmentImmediateSender $immediateSender;

    private AppointmentDeliveryStatusSyncer $deliveryStatusSyncer;

    public function boot(AppointmentImmediateSender $immediateSender, AppointmentDeliveryStatusSyncer $deliveryStatusSyncer): void
    {
        $this->immediateSender = $immediateSender;
        $this->deliveryStatusSyncer = $deliveryStatusSyncer;
    }

    public function mount(int $clientId): void
    {
        abort_unless(Client::query()->whereKey($clientId)->exists(), 404);

        $this->clientId = $clientId;

        $this->deliveryStatusesSyncedAt = Cache::get('appointment_delivery_statuses_synced_at');
    }

    public function updated(string $property): void
    {
        if ($property === 'sort_direction') {
            if (! in_array($this->sort_direction, ['asc', 'desc'], true)) {
                $this->sort_direction = 'asc';
            }
        }

        if ($property === 'filter') {
            if (! in_array($this->filter, ['upcoming', 'past', 'all'], true)) {
                $this->filter = 'upcoming';
            }
        }

        $this->resetPage('appointmentsPage');
    }

    public function toggleSortDirection(): void
    {
        $this->sort_direction = $this->sort_direction === 'asc' ? 'desc' : 'asc';
        $this->resetPage('appointmentsPage');
    }

    public function toggleWhatsappFilter(string $value): void
    {
        $this->whatsappFilter = $this->whatsappFilter === $value ? null : $value;
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

    public function toggleVisibleAppointments(array $appointmentIds): void
    {
        $appointmentIds = array_values(array_unique(array_map('intval', $appointmentIds)));
        $selectedIds = array_values(array_unique(array_map('intval', $this->selectedAppointmentIds)));

        $this->selectedAppointmentIds = array_diff($appointmentIds, $selectedIds) === []
          ? array_values(array_diff($selectedIds, $appointmentIds))
          : array_values(array_unique([...$selectedIds, ...$appointmentIds]));

        $this->selectAll = $appointmentIds !== [] && array_diff($appointmentIds, $this->selectedAppointmentIds) === [];
    }

    public function confirmBulkDelete(): void
    {
        $this->bulkDeleteConfirmationOpen = $this->selectedAppointmentIds !== [];
    }

    public function deleteSelected(): void
    {
        if ($this->selectedAppointmentIds === []) {
            return;
        }

        $deleted = app(ClientDataDeletionService::class)
            ->deleteAppointments($this->selectedAppointmentIds, $this->clientId);

        $this->selectedAppointmentIds = [];
        $this->selectAll = false;
        $this->bulkDeleteConfirmationOpen = false;

        $this->redirectAfterAction(sprintf('%d cita(s) eliminada(s) correctamente.', $deleted));
    }

    public function updateSelectedActiveStatus(bool $activo): void
    {
        if ($this->selectedAppointmentIds === []) {
            return;
        }

        $appointmentIds = Appointment::query()
            ->where('client_id', $this->clientId)
            ->whereKey(array_map('intval', $this->selectedAppointmentIds))
            ->pending()
            ->upcoming()
            ->pluck('id');

        Appointment::query()->whereKey($appointmentIds)->update(['activo' => $activo]);

        if (! $activo) {
            WhatsAppMessage::query()
                ->whereIn('appointment_id', $appointmentIds)
                ->where('status', WhatsAppMessage::STATUS_PENDING)
                ->delete();
        }

        $this->selectedAppointmentIds = [];
        $this->selectAll = false;

        $this->dispatch('toast', message: sprintf(
            '%d cita(s) %s correctamente.',
            $appointmentIds->count(),
            $activo ? 'activada(s)' : 'desactivada(s)'
        ), type: 'success');

        $this->render();
    }

    public function updateSelectedCitaActiva(bool $citaActiva): void
    {
        if ($this->selectedAppointmentIds === []) {
            return;
        }

        $appointmentIds = Appointment::query()
            ->where('client_id', $this->clientId)
            ->whereKey(array_map('intval', $this->selectedAppointmentIds))
            ->pluck('id');

        Appointment::query()->whereKey($appointmentIds)->update(['cita_activa' => $citaActiva]);

        $this->selectedAppointmentIds = [];
        $this->selectAll = false;

        $this->dispatch('toast', message: sprintf(
            '%d cita(s) %s correctamente.',
            $appointmentIds->count(),
            $citaActiva ? 'activada(s)' : 'desactivada(s)'
        ), type: 'success');

        $this->render();
    }

    public function deleteConfirmed(): void
    {
        if (! $this->appointmentPendingDeletionId) {
            return;
        }

        app(ClientDataDeletionService::class)
            ->deleteAppointments([$this->appointmentPendingDeletionId], $this->clientId);

        $this->appointmentPendingDeletionId = null;

        $this->redirectAfterAction('Cita eliminada correctamente.');
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
            $this->dispatch('toast', message: 'Esta cita no se puede modificar. Solo se puede eliminar.', type: 'error');

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

        $this->dispatch('toast', message: 'Estado pendiente actualizado.', type: 'success');
    }

    public function updateAppointmentActiveStatus(int $appointmentId, bool|string $citaActiva): void
    {
        if (is_bool($citaActiva)) {
            $isActive = $citaActiva;
        } elseif (in_array($citaActiva, ['0', '1'], true)) {
            $isActive = $citaActiva === '1';
        } else {
            return;
        }

        $appointment = Appointment::query()->findOrFail($appointmentId);

        if (! $appointment->canBeChanged()) {
            $this->dispatch('toast', message: 'Esta cita no se puede modificar. Solo se puede eliminar.', type: 'error');

            return;
        }

        $appointment->update(['cita_activa' => $isActive]);

        $this->dispatch('toast', message: 'Estado de la cita actualizado.', type: 'success');
    }

    public function sendNow(int $appointmentId, WhatsAppSender $sender): void
    {
        $appointment = Appointment::query()
            ->with('client')
            ->findOrFail($appointmentId);

        if ($appointment->enviado) {
            $this->dispatch('toast', message: 'Esta cita ya tiene el WhatsApp enviado.', type: 'error');

            return;
        }

        if (! $appointment->scheduledFor()->isFuture()) {
            $this->dispatch('toast', message: 'Las citas pasadas no pueden enviarse.', type: 'error');

            return;
        }

        if (! $appointment->activo) {
            $this->dispatch('toast', message: 'Las citas no pendientes no pueden enviarse.', type: 'error');

            return;
        }

        $client = $appointment->client;

        if (! $client) {
            $this->dispatch('toast', message: 'No se pudo enviar el WhatsApp porque la cita no tiene cliente asociado.', type: 'error');

            return;
        }

        $result = $this->immediateSender->send(
            $appointment,
            $client,
            $sender,
            'WhatsApp enviado ahora correctamente.',
            'No se pudo enviar el WhatsApp.'
        );

        if ($result['sent']) {
            $this->queuePageReloadAfterWhatsAppSend();
        }

        $this->dispatch('toast', message: $result['message'], type: str_contains($result['message'], 'correctamente') ? 'success' : 'error');
    }

    public function confirmResend(int $appointmentId): void
    {
        $this->appointmentPendingResendId = $appointmentId;
    }

    public function cancelResend(): void
    {
        $this->appointmentPendingResendId = null;
    }

    public function resendConfirmed(WhatsAppSender $sender): void
    {
        if (! $this->appointmentPendingResendId) {
            return;
        }

        $appointment = Appointment::query()->with('client')->findOrFail($this->appointmentPendingResendId);

        if (! $appointment->enviado || ! $appointment->scheduledFor()->isFuture()) {
            $this->dispatch('toast', message: 'Esta cita no se puede reenviar.', type: 'error');

            return;
        }

        if (! $appointment->client) {
            $this->dispatch('toast', message: 'No se pudo reenviar el WhatsApp porque la cita no tiene cliente asociado.', type: 'error');

            return;
        }

        $result = $this->immediateSender->send(
            $appointment,
            $appointment->client,
            $sender,
            'WhatsApp reenviado correctamente.',
            'No se pudo reenviar el WhatsApp.'
        );

        $this->appointmentPendingResendId = null;

        $this->dispatch('toast', message: $result['message'], type: str_contains($result['message'], 'correctamente') ? 'success' : 'error');
    }

    public function openHistory(int $appointmentId): void
    {
        $this->historyAppointment = Appointment::query()
            ->with([
                'changes',
                'whatsAppMessages' => fn ($q) => $q->orderBy('sent_at', 'asc')->orderBy('id', 'asc'),
            ])
            ->findOrFail($appointmentId);
    }

    public function closeHistory(): void
    {
        $this->historyAppointment = null;
    }

    public function syncDeliveryStatuses(): void
    {
        $updated = $this->deliveryStatusSyncer->syncAll($this->clientId, force: true);
        $this->deliveryStatusesSyncedAt = now(config('app.timezone'))->format('H:i - d/m/Y');
        Cache::forever('appointment_delivery_statuses_synced_at', $this->deliveryStatusesSyncedAt);

        if ($updated > 0) {
            $this->dispatch('toast', message: $updated === 1 ? 'Se ha actualizado 1 cita' : 'Se han actualizado '.$updated.' citas', type: 'success');

            return;
        }

        $this->dispatch('toast', message: 'Todos los registros de citas y demás datos están actualizados.', type: 'success');
    }

    public function autoSync(): void
    {
        $this->deliveryStatusSyncer->syncAll($this->clientId, force: true);
        $this->deliveryStatusesSyncedAt = now(config('app.timezone'))->format('H:i - d/m/Y');
        Cache::forever('appointment_delivery_statuses_synced_at', $this->deliveryStatusesSyncedAt);
    }

    private function queuePageReloadAfterWhatsAppSend(): void
    {
        $this->dispatch('reload-appointment-list');
    }

    public function render()
    {
        $selectedClient = Client::query()->find($this->clientId);
        $appointmentsQuery = $this->appointmentsQuery($selectedClient);

        $appointments = $appointmentsQuery->paginate(30, ['appointments.*'], 'appointmentsPage');

        $visibleAppointmentIds = $appointments->getCollection()->pluck('id')->all();
        $allVisibleAppointmentsSelected = $visibleAppointmentIds !== []
          && array_diff($visibleAppointmentIds, array_map('intval', $this->selectedAppointmentIds)) === [];

        $appointmentPendingDeletion = $this->appointmentPendingDeletionId
          ? Appointment::query()->with('client')->find($this->appointmentPendingDeletionId)
          : null;

        $appointmentPendingResend = $this->appointmentPendingResendId
          ? Appointment::query()->with('client')->find($this->appointmentPendingResendId)
          : null;

        return view('livewire.client-appointments', [
            'appointments' => $appointments,
            'appointmentsCount' => $appointments->total(),
            'appointmentPendingDeletion' => $appointmentPendingDeletion,
            'appointmentPendingResend' => $appointmentPendingResend,
            'selectedClient' => $selectedClient,
            'showBulkActions' => true,
            'visibleAppointmentIds' => $visibleAppointmentIds,
            'allVisibleAppointmentsSelected' => $allVisibleAppointmentsSelected,
        ]);
    }

    private function appointmentsQuery(Client $selectedClient): Builder
    {
        $now = now(config('app.timezone'));

        return Appointment::query()
            ->select('appointments.*')
            ->withCount('changes')
            ->with(['client', 'latestWhatsAppMessage', 'latestRespondedWhatsAppMessage'])
            ->leftJoin('clients', 'clients.id', '=', 'appointments.client_id')
            ->where('appointments.client_id', $selectedClient->id)
            ->when($this->filter === 'upcoming', fn ($q) => $q
                ->whereDate('fecha', '>=', $now->toDateString())
            )
            ->when($this->filter === 'past', fn ($q) => $q
                ->whereDate('fecha', '<', $now->toDateString())
            )
            ->when($this->whatsappFilter === 'sent', fn ($q) => $q->where('enviado', true))
            ->when($this->whatsappFilter === 'delivered', fn ($q) => $q->where('entregado', true))
            ->when($this->whatsappFilter === 'unsent', fn ($q) => $q->where('activo', false))
            ->orderBy('appointments.fecha', $this->sort_direction)
            ->orderBy('appointments.hora', $this->sort_direction);
    }

    private function redirectAfterAction(string $status): void
    {
        $client = Client::query()->find($this->clientId);

        if (! $this->appointmentsQuery($client)->exists()) {
            session()->flash('status', 'No hay citas para el cliente '.$client->full_name);
            $this->redirect(route('appointments.index'));

            return;
        }

        session()->flash('status', $status);
        $this->redirect(route('clients.appointments', $client));
    }
}
