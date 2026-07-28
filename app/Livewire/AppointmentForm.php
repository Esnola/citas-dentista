<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Client;
use App\Services\WhatsApp\AppointmentDeliveryStatusSyncer;
use App\Services\WhatsApp\AppointmentImmediateSender;
use App\Services\WhatsApp\WhatsAppSender;
use App\Traits\ValidatesSelectableDate;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AppointmentForm extends Component
{
    use ValidatesSelectableDate;

    public string $filter_nombre = '';

    public string $filter_apellidos = '';

    public string $filter_telefono = '';

    public ?int $selectedClientId = null;

    public ?int $selectedAppointmentId = null;

    public string $fecha = '';

    public string $hora = '';

    public bool $enviado = false;

    public bool $activo = true;

    public bool $sendImmediately = true;

    public bool $isEditing = false;

    public bool $hideClientSearch = false;

    public bool $showReturnAfterImmediateSend = false;

    public string $returnUrl = '';

    public function boot(AppointmentImmediateSender $immediateSender, AppointmentDeliveryStatusSyncer $deliveryStatusSyncer): void
    {
        $this->immediateSender = $immediateSender;
        $this->deliveryStatusSyncer = $deliveryStatusSyncer;
    }

    public function mount(): void
    {
        $this->returnUrl = $this->resolveReturnUrl();

        $appointmentId = (int) request()->route('appointment');

        if ($appointmentId > 0) {
            $this->isEditing = true;
            $this->loadAppointment($appointmentId);

            return;
        }

        $clientId = request()->integer('client');

        if ($clientId > 0) {
            $this->hideClientSearch = true;
            $this->selectClient($clientId);
        }
    }

    private AppointmentImmediateSender $immediateSender;

    private AppointmentDeliveryStatusSyncer $deliveryStatusSyncer;

    public function selectClient(int $clientId): void
    {
        if (! $this->canChangeAppointment) {
            session()->flash('status', 'Esta cita no se puede modificar. Solo se puede eliminar.');
            $this->redirect(url()->previous());

            return;
        }

        $client = Client::query()->findOrFail($clientId);

        $this->selectedClientId = $client->id;
        $this->returnUrl = route('clients.appointments', $client);
    }

    public function save(WhatsAppSender $sender): void
    {
        $data = $this->validate();
        $this->validateSelectableDate($data['fecha'], 'fecha');

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
                $this->redirect($this->returnUrl ?: url()->previous());

                return;
            }

            if ($this->hasAppointmentDateConflict) {
                $this->addError('fecha', 'Ya existe una cita para esa fecha y hora.');

                return;
            }

            $scheduleChanged = $appointment->fecha?->toDateString() !== $payload['fecha']
                || mb_substr((string) $appointment->hora, 0, 5) !== mb_substr((string) $payload['hora'], 0, 5);

            $appointment->update($payload);

            if ($scheduleChanged && (bool) $data['sendImmediately'] && $appointment->isFuture()) {
                $this->sendAppointmentNow(
                    $appointment,
                    $client,
                    $sender,
                    'Cita actualizada correctamente y WhatsApp de cambio enviado ahora.',
                    'Cita actualizada, pero no se pudo enviar el WhatsApp de cambio.',
                    false,
                    WhatsAppSender::TEMPLATE_SCOPE_APPOINTMENT_CHANGED,
                );

                return;
            }

            session()->flash('status', 'Cita actualizada correctamente.');
            $this->redirect($this->returnUrl ?: url()->previous());
        } else {
            if ($this->hasAppointmentDateConflict) {
                $this->addError('fecha', 'Ya existe una cita para esa fecha y hora.');

                return;
            }

            $appointment = Appointment::query()->create($payload);
            $this->selectedAppointmentId = $appointment->id;

            if ((bool) $data['sendImmediately'] && $appointment->isFuture()) {
                $this->sendAppointmentNow(
                    $appointment,
                    $client,
                    $sender,
                    'Cita creada correctamente y WhatsApp enviado ahora.',
                    'Cita creada, pero no se pudo enviar el WhatsApp.',
                    false,
                    WhatsAppSender::TEMPLATE_SCOPE_APPOINTMENT_CREATED,
                );

                return;
            }

            session()->flash('status', 'Cita creada correctamente.');

            $this->redirect(url()->previous());
        }
    }

    public function sendNow(WhatsAppSender $sender): void
    {
        if (! $this->selectedAppointmentId) {
            return;
        }

        $appointment = Appointment::query()
            ->with('client')
            ->findOrFail($this->selectedAppointmentId);

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
            session()->flash('status', 'Las citas con comunicaciones desactivadas no pueden enviarse.');
            $this->redirect(url()->previous());

            return;
        }

        $client = $appointment->client;

        if (! $client) {
            session()->flash('status', 'No se pudo enviar el WhatsApp porque la cita no tiene cliente asociado.');
            $this->redirect(url()->previous());

            return;
        }

        $this->sendAppointmentNow(
            $appointment,
            $client,
            $sender,
            'WhatsApp enviado ahora correctamente.',
            'No se pudo enviar el WhatsApp.',
            true,
            WhatsAppSender::TEMPLATE_SCOPE_APPOINTMENT_REMINDER,
        );
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

        $appointment = Appointment::query()
            ->whereKey($this->selectedAppointmentId)
            ->first();

        return (bool) $appointment && $appointment->canBeChanged();
    }

    public function getCanSendAppointmentNowProperty(): bool
    {
        if (! $this->selectedAppointmentId) {
            return false;
        }

        $appointment = Appointment::query()
            ->whereKey($this->selectedAppointmentId)
            ->first();

        return (bool) $appointment && ! $appointment->enviado && $appointment->activo && $appointment->isFuture();
    }

    public function getHasScheduleChangesProperty(): bool
    {
        if (! $this->selectedAppointmentId) {
            return true;
        }

        $appointment = Appointment::query()
            ->whereKey($this->selectedAppointmentId)
            ->first();

        if (! $appointment) {
            return false;
        }

        return $appointment->fecha?->toDateString() !== $this->fecha
            || mb_substr((string) $appointment->hora, 0, 5) !== mb_substr($this->hora, 0, 5);
    }

    public function getCanSaveAppointmentProperty(): bool
    {
        if (! $this->canChangeAppointment) {
            return false;
        }

        if ($this->hasAppointmentDateConflict) {
            return false;
        }

        return ! $this->selectedAppointmentId || $this->hasScheduleChanges;
    }

    public function getHasAppointmentDateConflictProperty(): bool
    {
        return $this->appointmentDateConflictExists();
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
        $clientsQuery = Client::query()
            ->when($this->filter_nombre, fn ($query) => $query->where('nombre', 'like', '%'.$this->filter_nombre.'%'))
            ->when($this->filter_apellidos, fn ($query) => $query->where('apellidos', 'like', '%'.$this->filter_apellidos.'%'))
            ->when($this->filter_telefono, fn ($query) => $query->where('telefono', 'like', '%'.$this->filter_telefono.'%'));

        $clientResultsCount = $this->hasClientSearch
            ? (clone $clientsQuery)->count()
            : 0;

        $clients = $this->hasClientSearch
            ? $clientsQuery
                ->orderByDesc('created_at')
                ->limit(10)
                ->get()
            : collect();

        $appointmentHistory = $this->selectedClientId
            ? Appointment::query()
                ->where('client_id', $this->selectedClientId)
                ->whereDate('fecha', '>', now(config('app.timezone'))->toDateString())
                ->select(['id', 'fecha', 'hora'])
                ->orderBy('fecha')
                ->orderBy('hora')
                ->get()
            : collect();

        return view('livewire.appointment-form', [
            'clients' => $clients,
            'selectedClient' => $this->selectedClient,
            'selectedAppointment' => $this->selectedAppointment,
            'appointmentHistory' => $appointmentHistory,
            'isEditing' => $this->isEditing,
            'hideClientSearch' => $this->hideClientSearch,
            'canChangeAppointment' => $this->canChangeAppointment,
            'canSaveAppointment' => $this->canSaveAppointment,
            'canSendAppointmentNow' => $this->canSendAppointmentNow,
            'hasAppointmentDateConflict' => $this->hasAppointmentDateConflict,
            'showReturnAfterImmediateSend' => $this->showReturnAfterImmediateSend,
            'returnUrl' => $this->returnUrl,
            'hasClientSearch' => $this->hasClientSearch,
            'hasMoreThanTenClientResults' => $clientResultsCount > 10,
            'minimumSelectableDate' => $this->minimumSelectableDate(),
        ])->extends('layouts.app')->section('content');
    }

    protected function rules(): array
    {
        return [
            'selectedClientId' => [
                'required',
                'integer',
                Rule::exists('clients', 'id'),
            ],
            'fecha' => ['required', 'date', Rule::date()->afterOrEqual('today')],
            'hora' => ['required', 'date_format:H:i'],
            'enviado' => ['boolean'],
            'activo' => ['boolean'],
            'sendImmediately' => ['boolean'],
        ];
    }

    protected function messages(): array
    {
        return [
            'fecha.after' => 'La fecha no puede ser anterior a hoy.',
        ];
    }

    private function sendAppointmentNow(
        Appointment $appointment,
        Client $client,
        WhatsAppSender $sender,
        string $successMessage,
        string $failureMessage,
        bool $showReturnAfterSuccess = false,
        string $templateScope = WhatsAppSender::TEMPLATE_SCOPE_APPOINTMENT_REMINDER,
    ): void {
        $result = $this->immediateSender->send($appointment, $client, $sender, $successMessage, $failureMessage, $templateScope);

        if ($result['sent']) {
            $appointment->refresh();

            $this->enviado = $templateScope === WhatsAppSender::TEMPLATE_SCOPE_APPOINTMENT_CHANGED
                ? (bool) $appointment->enviado
                : true;
            $this->showReturnAfterImmediateSend = $showReturnAfterSuccess;
        }

        session()->flash('status', $result['message']);

        $this->redirect(url()->previous());
    }

    private function minimumSelectableDate(): string
    {
        return now()->toDateString();
    }

    private function resolveReturnUrl(): string
    {
        $previousUrl = url()->previous();
        $currentUrl = url()->current();

        return $previousUrl !== $currentUrl
            ? $previousUrl
            : route('appointments.index');
    }

    private function appointmentDateConflictExists(): bool
    {
        if (! $this->fecha || ! $this->hora) {
            return false;
        }

        $hora = mb_substr($this->hora, 0, 5);

        return Appointment::query()
            ->whereDate('fecha', $this->fecha)
            ->where(fn ($query) => $query
                ->where('hora', $hora)
                ->orWhere('hora', $hora.':00'))
            ->when($this->selectedAppointmentId, fn ($query) => $query->whereKeyNot($this->selectedAppointmentId))
            ->exists();
    }

    private function loadAppointment(int $appointmentId): void
    {
        $appointment = Appointment::query()->with('client')->findOrFail($appointmentId);
        $this->deliveryStatusSyncer->sync([$appointment->id]);
        $appointment->refresh();

        $this->selectedAppointmentId = $appointment->id;
        $this->selectedClientId = $appointment->client_id;
        $this->returnUrl = route('clients.appointments', $appointment->client_id);
        $this->fecha = $appointment->fecha?->toDateString() ?? '';
        $this->hora = mb_substr((string) $appointment->hora, 0, 5);
        $this->enviado = (bool) $appointment->enviado;
        $this->activo = true;
        $this->sendImmediately = true;
    }
}
