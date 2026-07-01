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

    public string $statusType = 'neutral';

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
        try {
            $this->validate([
                'file' => ['required', 'file', 'extensions:csv,txt', 'mimetypes:text/plain,text/csv,application/csv,application/vnd.ms-excel,text/comma-separated-values', 'max:10240'],
                'template_key' => ['required', 'in:'.implode(',', array_column(WhatsAppMessage::templateOptions(), 'key'))],
            ]);

            $delimiter = $this->detectCsvDelimiter();
            $import = new WhatsAppMessagesImport(Auth::user(), $this->template_key, false, $delimiter);
            Excel::import($import, $this->file);

            $this->previewRows = $import->previewRows();
            $this->previewLoaded = true;
            $this->setStatus('Previsualización generada correctamente.');
        } catch (\Throwable $throwable) {
            $this->setStatus('No se pudo generar la previsualización: '.$throwable->getMessage(), 'error');
        }
    }

    public function import(): void
    {
        try {
            $this->validate([
                'file' => ['required', 'file', 'extensions:csv,txt', 'mimetypes:text/plain,text/csv,application/csv,application/vnd.ms-excel,text/comma-separated-values', 'max:10240'],
                'template_key' => ['required', 'in:'.implode(',', array_column(WhatsAppMessage::templateOptions(), 'key'))],
            ]);

            $delimiter = $this->detectCsvDelimiter();
            Excel::import(
                new WhatsAppMessagesImport(Auth::user(), $this->template_key, true, $delimiter),
                $this->file
            );

            $this->reset('file');
            $this->setStatus('Archivo importado correctamente.');
        } catch (\Throwable $throwable) {
            $this->setStatus('No se pudo importar el CSV: '.$throwable->getMessage(), 'error');
        }
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

    private function setStatus(string $message, string $type = 'success'): void
    {
        $this->status = $message;
        $this->statusType = $type;
    }

    private function detectCsvDelimiter(): string
    {
        $path = $this->file?->getRealPath();

        if (! is_string($path) || $path === '') {
            return ',';
        }

        $sample = file_get_contents($path, false, null, 0, 4096);

        if (! is_string($sample) || $sample === '') {
            return ',';
        }

        $sample = preg_replace('/^\xEF\xBB\xBF/', '', $sample) ?? $sample;
        $counts = [
            ';' => substr_count($sample, ';'),
            ',' => substr_count($sample, ','),
            "\t" => substr_count($sample, "\t"),
        ];

        arsort($counts);

        $delimiter = array_key_first($counts);

        return is_string($delimiter) && $counts[$delimiter] > 0 ? $delimiter : ',';
    }
}
