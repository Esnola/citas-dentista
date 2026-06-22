<?php

namespace App\Livewire;

use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class MessageManager extends Component
{
    use WithPagination;

    public string $filter_nombre = '';
    public string $filter_apellidos = '';
    public string $filter_telefono = '';

    public ?int $selected_message_id = null;
    public string $scheduled_date = '';
    public string $scheduled_time = '';
    public string $template_key = '';

    public function mount(): void
    {
        $this->template_key = \App\Models\WhatsAppTemplate::defaultKey();
    }

    public function updatedFilterNombre(): void
    {
        $this->resetPage('messagesPage');
    }

    public function updatedFilterApellidos(): void
    {
        $this->resetPage('messagesPage');
    }

    public function updatedFilterTelefono(): void
    {
        $this->resetPage('messagesPage');
    }

    public function selectMessage(int $messageId): void
    {
        $message = WhatsAppMessage::query()
            ->with('client')
            ->where('user_id', Auth::id())
            ->findOrFail($messageId);

        $this->selected_message_id = $message->id;
        $this->scheduled_date = $message->scheduled_for?->toDateString() ?? now()->toDateString();
        $this->scheduled_time = $message->scheduled_for?->format('H:i') ?? now()->format('H:i');
        $this->template_key = $message->metadata['template_key'] ?? $this->template_key;
    }

    public function clearSelection(): void
    {
        $this->selected_message_id = null;
        $this->scheduled_date = '';
        $this->scheduled_time = '';
    }

    protected function rules(): array
    {
        $templateKeys = implode(',', array_column(WhatsAppMessage::templateOptions(), 'key'));

        return [
            'selected_message_id' => [
                'required',
                'integer',
                Rule::exists('whatsapp_messages', 'id')->where(fn ($query) => $query->where('user_id', Auth::id())),
            ],
            'scheduled_date' => ['required', 'date'],
            'scheduled_time' => ['required', 'date_format:H:i'],
            'template_key' => ['required', 'in:'.$templateKeys],
        ];
    }

    public function save(): void
    {
        $data = $this->validate();
        $selectedMessage = WhatsAppMessage::query()
            ->where('user_id', Auth::id())
            ->findOrFail($data['selected_message_id']);

        $scheduledFor = Carbon::parse("{$data['scheduled_date']} {$data['scheduled_time']}");
        $message = WhatsAppMessage::buildMessage([
            'nombre' => $selectedMessage->nombre,
            'apellidos' => $selectedMessage->apellidos,
            'telefono' => $selectedMessage->telefono,
            'scheduled_for' => $scheduledFor,
        ], $data['template_key']);

        WhatsAppMessage::create([
            'user_id' => Auth::id(),
            'client_id' => $selectedMessage->client_id,
            'nombre' => $selectedMessage->nombre,
            'apellidos' => $selectedMessage->apellidos,
            'telefono' => $selectedMessage->telefono,
            'scheduled_for' => $scheduledFor,
            'message' => $message,
            'source' => $selectedMessage->source,
            'status' => WhatsAppMessage::STATUS_PENDING,
            'metadata' => [
                'template_key' => $data['template_key'],
                'origin_message_id' => $selectedMessage->id,
            ],
        ]);

        $this->clearSelection();
        $this->template_key = \App\Models\WhatsAppTemplate::defaultKey();

        session()->flash('status', 'Mensaje programado correctamente.');
    }

    public function delete(int $messageId): void
    {
        $message = WhatsAppMessage::query()
            ->where('user_id', Auth::id())
            ->findOrFail($messageId);

        $message->delete();

        session()->flash('status', 'Mensaje eliminado.');
    }

    public function render()
    {
        $messages = WhatsAppMessage::query()
            ->with('client')
            ->where('user_id', Auth::id())
            ->when($this->filter_nombre, fn ($query) => $query->where('nombre', 'like', '%'.$this->filter_nombre.'%'))
            ->when($this->filter_apellidos, fn ($query) => $query->where('apellidos', 'like', '%'.$this->filter_apellidos.'%'))
            ->when($this->filter_telefono, fn ($query) => $query->where('telefono', 'like', '%'.$this->filter_telefono.'%'))
            ->latest('scheduled_for')
            ->paginate(10, ['*'], 'messagesPage');

        $selectedMessage = $this->selected_message_id
            ? WhatsAppMessage::query()->with('client')->where('user_id', Auth::id())->find($this->selected_message_id)
            : null;

        return view('livewire.message-manager', [
            'templateOptions' => WhatsAppMessage::templateOptions(),
            'previewMessage' => $selectedMessage
                ? WhatsAppMessage::buildMessage([
                    'nombre' => $selectedMessage->nombre,
                    'apellidos' => $selectedMessage->apellidos,
                    'telefono' => $selectedMessage->telefono,
                    'scheduled_for' => Carbon::parse(
                        ($this->scheduled_date ?: now()->toDateString()).' '.($this->scheduled_time ?: now()->format('H:i'))
                    ),
                ], $this->template_key ?: config('whatsapp.default_template'))
                : 'Selecciona un registro para ver la previsualización del mensaje.',
            'messages' => $messages,
            'selectedMessage' => $selectedMessage,
        ]);
    }
}
