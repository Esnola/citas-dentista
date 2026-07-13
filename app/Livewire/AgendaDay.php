<?php

namespace App\Livewire;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Throwable;

class AgendaDay extends Component
{
    public string $date;

    public function mount(string $date): void
    {
        try {
            $parsedDate = Carbon::createFromFormat('Y-m-d', $date, config('app.timezone'))->startOfDay();
        } catch (Throwable) {
            abort(404);
        }

        abort_unless($parsedDate->toDateString() === $date && ! $parsedDate->isSunday(), 404);

        $this->date = $date;
    }

    public function render(): View
    {
        Carbon::setLocale('es');
        $selectedDate = Carbon::parse($this->date, config('app.timezone'))->startOfDay();

        return view('livewire.agenda-day', [
            'selectedDate' => $selectedDate,
            'appointments' => $this->appointments(),
        ]);
    }

    private function appointments(): Collection
    {
        return Appointment::query()
            ->with(['client', 'whatsAppMessages'])
            ->whereDate('fecha', $this->date)
            ->orderBy('hora')
            ->get();
    }

    /** @return array{label: string, classes: string}|null */
    public function whatsappStatusBadge(Appointment $appointment): ?array
    {
        $buttonBadge = $this->latestButtonResponseBadge($appointment);

        if ($buttonBadge) {
            return $buttonBadge;
        }
        $laClase = 'border-gray-500/20 bg-gray-500/10 text-gray-300 shadow-gray-300/50 ';
        $elIcono = 'doble-check';
        if ($appointment->whatsapp_read_at) {
            return [
                'label' => 'Leído',
                'classes' => $laClase,
                'icono' => $elIcono,
            ];
        }

        if ($appointment->entregado) {
            return [
                'label' => 'Entregado',
                'classes' => $laClase,
                'icono' => $elIcono,
            ];
        }

        if ($appointment->enviado) {
            return [
                'label' => 'Enviado',
                'classes' => $laClase,
                'icono' => $elIcono,

            ];
        }

        return null;
    }

    /** @return array{label: string, classes: string}|null */
    private function latestButtonResponseBadge(Appointment $appointment): ?array
    {
        $messages = $appointment->relationLoaded('whatsAppMessages')
          ? $appointment->whatsAppMessages
          : $appointment->whatsAppMessages()->get();

        $message = $messages
            ->filter(fn ($message): bool => $message->direction === 'inbound')
            ->filter(fn ($message): bool => filled(data_get($message->provider_payload, 'inbound.button_text'))
              || filled(data_get($message->provider_payload, 'inbound.button_payload'))
              || $message->isConfirmed()
              || $message->isRescheduleRequested())
            ->sortByDesc(fn ($message): int => ($message->responded_at ?? $message->created_at)?->timestamp ?? 0)
            ->first();

        if (! $message) {
            return null;
        }

        if ($message->isConfirmed()) {
            return [
                'label' => 'Cita Confirmada',
                'classes' => 'border-emerald-400/30 bg-emerald-500/15 text-emerald-300 shadow-emerald-300/50 ',
                'icono' => 'ok',
            ];
        }

        if ($message->isRescheduleRequested()) {
            return [
                'label' => 'Cambiar cita',
                'classes' => 'border-red-400/30 bg-red-500/15 text-red-300 shadow-red-300/50 ',
                'icono' => 'alert',
            ];
        }

        $label = trim((string) data_get($message->provider_payload, 'inbound.button_text', ''));
        $label = $label !== '' ? $label : trim((string) data_get($message->provider_payload, 'inbound.response_text', ''));

        if ($label === '') {
            return null;
        }

        return [
            'label' => $label,
            'classes' => 'border-indigo-400/30 bg-indigo-500/15 text-indigo-200',
        ];
    }
}
