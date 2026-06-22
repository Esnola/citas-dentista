<?php

namespace App\Livewire;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class ClientManager extends Component
{
    use WithPagination;

    public string $filter_nombre = '';
    public string $filter_apellidos = '';
    public string $filter_telefono = '';

    public ?int $selectedClientId = null;
    public string $nombre = '';
    public string $apellidos = '';
    public string $telefono = '';

    public function mount(): void
    {
        $clientId = request()->integer('client');

        if ($clientId > 0) {
            $this->selectClient($clientId);
        }
    }

    public function updatedFilterNombre(): void
    {
        $this->resetPage();
    }

    public function updatedFilterApellidos(): void
    {
        $this->resetPage();
    }

    public function updatedFilterTelefono(): void
    {
        $this->resetPage();
    }

    public function selectClient(int $clientId): void
    {
        $client = Client::query()->findOrFail($clientId);

        $this->selectedClientId = $client->id;
        $this->nombre = $client->nombre;
        $this->apellidos = $client->apellidos;
        $this->telefono = $client->telefono;
    }

    public function clearSelection(): void
    {
        $this->selectedClientId = null;
        $this->nombre = '';
        $this->apellidos = '';
        $this->telefono = '';
        $this->resetValidation();
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
            $this->statusMessage('Cliente actualizado correctamente.');
        } else {
            $client = Client::query()->updateOrCreate(
                ['telefono' => $payload['telefono']],
                $payload
            );
            $this->selectedClientId = $client->id;
            $this->statusMessage($client->wasRecentlyCreated ? 'Cliente creado correctamente.' : 'Cliente actualizado correctamente.');
        }

        $this->resetPage();
    }

    public function delete(int $clientId): void
    {
        Client::query()->whereKey($clientId)->delete();

        if ($this->selectedClientId === $clientId) {
            $this->clearSelection();
        }

        $this->statusMessage('Cliente eliminado correctamente.');
    }

    public function getSelectedClientProperty(): ?Client
    {
        return $this->selectedClientId
            ? Client::query()->find($this->selectedClientId)
            : null;
    }

    public function render()
    {
        $clients = Client::query()
            ->when($this->filter_nombre, fn ($query) => $query->where('nombre', 'like', '%'.$this->filter_nombre.'%'))
            ->when($this->filter_apellidos, fn ($query) => $query->where('apellidos', 'like', '%'.$this->filter_apellidos.'%'))
            ->when($this->filter_telefono, fn ($query) => $query->where('telefono', 'like', '%'.$this->filter_telefono.'%'))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.client-manager', [
            'clients' => $clients,
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

    private function statusMessage(string $message): void
    {
        session()->flash('status', $message);
    }
}
