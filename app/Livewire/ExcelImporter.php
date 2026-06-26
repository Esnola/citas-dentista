<?php

namespace App\Livewire;

use App\Imports\WhatsAppMessagesImport;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppTemplate;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImporter extends Component
{
    use WithFileUploads;

    public ?TemporaryUploadedFile $file = null;

    public string $status = '';

    public string $template_key = '';

    public string $filter_nombre = '';

    public string $filter_apellidos = '';

    public string $filter_telefono = '';

    public array $previewRows = [];

    public bool $previewLoaded = false;

    public function mount(): void
    {
        $this->template_key = WhatsAppTemplate::defaultKey();
    }

    public function preview(): void
    {
        $this->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
            'template_key' => ['required', 'in:'.implode(',', array_column(WhatsAppMessage::templateOptions(), 'key'))],
        ]);

        $import = new WhatsAppMessagesImport(Auth::user(), $this->template_key, false);
        Excel::import($import, $this->file);

        $this->previewRows = $import->previewRows();
        $this->previewLoaded = true;
        $this->status = 'Previsualización generada correctamente.';
        session()->flash('status', $this->status);
        $this->redirect(url()->previous());
    }

    public function import(): void
    {
        $this->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
            'template_key' => ['required', 'in:'.implode(',', array_column(WhatsAppMessage::templateOptions(), 'key'))],
        ]);

        Excel::import(
            new WhatsAppMessagesImport(Auth::user(), $this->template_key, true),
            $this->file
        );

        $this->reset('file', 'previewRows', 'previewLoaded');
        $this->status = 'Archivo importado correctamente.';
        session()->flash('status', $this->status);
        $this->redirect(url()->previous());
    }

    public function getFilteredPreviewRowsProperty(): array
    {
        return collect($this->previewRows)
            ->filter(fn (array $row) => $this->matchesFilter($row, 'nombre', $this->filter_nombre))
            ->filter(fn (array $row) => $this->matchesFilter($row, 'apellidos', $this->filter_apellidos))
            ->filter(fn (array $row) => $this->matchesFilter($row, 'telefono', $this->filter_telefono))
            ->values()
            ->all();
    }

    public function render()
    {
        return view('livewire.excel-importer', [
            'templateOptions' => WhatsAppMessage::templateOptions(),
            'filteredPreviewRows' => $this->filteredPreviewRows,
        ]);
    }

    private function matchesFilter(array $row, string $key, string $filter): bool
    {
        if ($filter === '') {
            return true;
        }

        return str_contains(mb_strtolower((string) ($row[$key] ?? '')), mb_strtolower($filter));
    }
}
