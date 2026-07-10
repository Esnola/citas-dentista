<?php

namespace App\Livewire;

use App\Models\Client;
use App\Services\WhatsApp\AppointmentDeliveryStatusSyncer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class AppointmentIndex extends Component
{
    use WithPagination;

    public ?string $deliveryStatusesSyncedAt = null;

    public function mount(): void
    {
        $this->deliveryStatusesSyncedAt = Cache::get('appointment_delivery_statuses_synced_at');
    }

    public function syncDeliveryStatuses(AppointmentDeliveryStatusSyncer $syncer): void
    {
        $updated = $syncer->syncAll(force: true);
        $this->deliveryStatusesSyncedAt = now(config('app.timezone'))->format('H:i - d/m/Y');
        Cache::forever('appointment_delivery_statuses_synced_at', $this->deliveryStatusesSyncedAt);

        session()->flash('status', $updated > 0
            ? ($updated === 1 ? 'Se ha actualizado 1 cita' : 'Se han actualizado '.$updated.' citas')
            : 'Todos los registros de citas y demás datos están actualizados.');
    }

    public function render()
    {
        $today = now(config('app.timezone'))->toDateString();

        $clients = Client::query()
            ->whereHas('appointments', fn (Builder $query) => $query->whereDate('fecha', '>=', $today))
            ->withCount([
                'appointments as appointments_count' => fn (Builder $query) => $query->whereDate('fecha', '>=', $today),
            ])
            ->with(['appointments' => fn (Builder|HasMany $query) => $query
                ->whereDate('fecha', '>=', $today)
                ->orderBy('fecha')
                ->orderBy('hora')
                ->limit(1)])
            ->orderBy('nombre')
            ->orderBy('apellidos')
            ->paginate(30, pageName: 'clientsPage');

        return view('livewire.appointment-index', [
            'clients' => $clients,
        ]);
    }
}
