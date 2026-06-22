<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ClientMessageScheduler extends Component
{
    use WithPagination;

    public string $filter_nombre = '';
    public string $filter_apellidos = '';
    public string $filter_telefono = '';

    public ?int $selectedClientId = null;
    public string $scheduled_date = '';
    public string $scheduled_time = '';
    public string $template_key = '';
    public string $status = '';

    public function mount(): void
    {
        $this->template_key = \App\Models\WhatsAppTemplate::defaultKey();
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
        $client = Client::query()->findOrFail($clientId);

        $this->selectedClientId = $client->id;
        $this->scheduled_date = now()->toDateString();
        $this->scheduled_time = now()->format('H:i');
        $this->template_key = $this->template_key ?: \App\Models\WhatsAppTemplate::defaultKey();
    }

    public function clearSelection(): void
    {
        $this->selectedClientId = null;
        $this->scheduled_date = '';
        $this->scheduled_time = '';
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        $client = Client::query()->findOrFail($data['selectedClientId']);
        $scheduledFor = Carbon::parse("{$data['scheduled_date']} {$data['scheduled_time']}");
        $message = WhatsAppMessage::buildMessage([
            'nombre' => $client->nombre,
            'apellidos' => $client->apellidos,
            'telefono' => $client->telefono,
            'scheduled_for' => $scheduledFor,
        ], $data['template_key']);

        WhatsAppMessage::create([
            'user_id' => Auth::id(),
            'client_id' => $client->id,
            'nombre' => $client->nombre,
            'apellidos' => $client->apellidos,
            'telefono' => $client->telefono,
            'scheduled_for' => $scheduledFor,
            'message' => $message,
            'source' => WhatsAppMessage::SOURCE_MANUAL,
            'status' => WhatsAppMessage::STATUS_PENDING,
            'metadata' => [
                'template_key' => $data['template_key'],
                'origin_client_id' => $client->id,
            ],
        ]);

        $this->clearSelection();
        $this->template_key = \App\Models\WhatsAppTemplate::defaultKey();

        $this->status = 'Mensaje programado desde la ficha del cliente.';
    }

    public function render()
    {
        $clients = Client::query()
            ->when($this->filter_nombre, fn ($query) => $query->where('nombre', 'like', '%'.$this->filter_nombre.'%'))
            ->when($this->filter_apellidos, fn ($query) => $query->where('apellidos', 'like', '%'.$this->filter_apellidos.'%'))
            ->when($this->filter_telefono, fn ($query) => $query->where('telefono', 'like', '%'.$this->filter_telefono.'%'))
            ->orderByDesc('created_at')
            ->paginate(8, ['*'], 'clientsPage');

        $selectedClient = $this->selectedClientId
            ? Client::query()->find($this->selectedClientId)
            : null;

        return view('livewire.client-message-scheduler', [
            'clients' => $clients,
            'selectedClient' => $selectedClient,
            'templateOptions' => WhatsAppMessage::templateOptions(),
            'previewMessage' => $selectedClient
                ? WhatsAppMessage::buildMessage([
                    'nombre' => $selectedClient->nombre,
                    'apellidos' => $selectedClient->apellidos,
                    'telefono' => $selectedClient->telefono,
                    'scheduled_for' => Carbon::parse(
                        ($this->scheduled_date ?: now()->toDateString()).' '.($this->scheduled_time ?: now()->format('H:i'))
                    ),
                ], $this->template_key ?: config('whatsapp.default_template'))
                : 'Selecciona un cliente para generar su cita.',
        ]);
    }

    protected function rules(): array
    {
        $templateKeys = implode(',', array_column(WhatsAppMessage::templateOptions(), 'key'));

        return [
            'selectedClientId' => ['required', 'integer', 'exists:clients,id'],
            'scheduled_date' => ['required', 'date'],
            'scheduled_time' => ['required', 'date_format:H:i'],
            'template_key' => ['required', 'in:'.$templateKeys],
        ];
    }
}
