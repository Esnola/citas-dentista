<?php

namespace App\Livewire\Settings;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class TableBackup extends Component
{
    use WithFileUploads;

    private const BOOL_FIELDS = [
        'is_admin', 'enviado', 'entregado', 'activo', 'cita_activa',
        'confirmada', 'pendiente_reprogramacion',
    ];

    public string $selectedTable = 'clients';

    public $importFile;

    public string $importStatus = '';

    public int $importStatusNonce = 0;

    public bool $confirmImport = false;

    public array $tables = [
        'clients' => 'Clientes',
        'appointments' => 'Citas',
        'users' => 'Usuarios',
    ];

    public function mount(): void
    {
        //
    }

    public function updatedImportFile(): void
    {
        $this->confirmImport = false;
        $this->importStatus = '';
    }

    public function importTable(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        if (! $this->importFile) {
            $this->importStatus = 'Selecciona un archivo.';
            $this->importStatusNonce++;

            return;
        }

        if (! $this->confirmImport) {
            $this->confirmImport = true;
            $this->importStatus = 'Pulsa de nuevo en Importar para confirmar.';
            $this->importStatusNonce++;

            return;
        }

        $originalName = $this->importFile->getClientOriginalName();
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if ($extension === 'json') {
            $this->importFromJson();
        } elseif ($extension === 'csv') {
            $this->importFromCsv();
        } else {
            $this->importStatus = 'Formato no soportado. Usa .json o .csv.';
            $this->importStatusNonce++;
            $this->confirmImport = false;
        }
    }

    private function importFromJson(): void
    {
        $json = file_get_contents($this->importFile->getRealPath());

        if ($json === false) {
            $this->importStatus = 'No se pudo leer el archivo.';
            $this->importStatusNonce++;
            $this->confirmImport = false;

            return;
        }

        $records = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->importStatus = 'JSON inválido: '.json_last_error_msg();
            $this->importStatusNonce++;
            $this->confirmImport = false;

            return;
        }

        $this->applyRecords($records);
    }

    private function importFromCsv(): void
    {
        $handle = fopen($this->importFile->getRealPath(), 'r');

        if ($handle === false) {
            $this->importStatus = 'No se pudo leer el CSV.';
            $this->importStatusNonce++;
            $this->confirmImport = false;

            return;
        }

        $headings = fgetcsv($handle);

        if ($headings === false) {
            fclose($handle);
            $this->importStatus = 'CSV vacío.';
            $this->importStatusNonce++;
            $this->confirmImport = false;

            return;
        }

        $records = [];

        while (($row = fgetcsv($handle)) !== false) {
            $record = array_combine($headings, $row);
            $records[] = $this->unflattenFromCsv($record);
        }

        fclose($handle);
        $this->applyRecords($records);
    }

    private function applyRecords(array $records): void
    {
        $count = count($records);

        match ($this->selectedTable) {
            'clients' => $this->importClients($records),
            'appointments' => $this->importAppointments($records),
            'users' => $this->importUsers($records),
        };

        $this->importStatus = "{$count} registro(s) importado(s) en {$this->tables[$this->selectedTable]}.";
        $this->importStatusNonce++;
        $this->confirmImport = false;
        $this->importFile = null;
    }

    private function importClients(array $records): void
    {
        foreach ($records as $record) {
            Client::upsertFromImport($record);
        }
    }

    private function importAppointments(array $records): void
    {
        foreach ($records as $record) {
            $existing = null;

            if (! empty($record['id'])) {
                $existing = Appointment::find($record['id']);
            }

            $data = [
                'client_id' => $record['client_id'],
                'fecha' => $record['fecha'],
                'hora' => $record['hora'],
                'fecha_original' => $record['fecha_original'] ?? null,
                'hora_original' => $record['hora_original'] ?? null,
                'enviado' => $record['enviado'] ?? false,
                'entregado' => $record['entregado'] ?? false,
                'activo' => $record['activo'] ?? true,
                'cita_activa' => $record['cita_activa'] ?? true,
                'confirmada' => $record['confirmada'] ?? false,
                'pendiente_reprogramacion' => $record['pendiente_reprogramacion'] ?? false,
            ];

            if ($existing) {
                $existing->update($data);
            } else {
                unset($record['id']);
                Appointment::create($data);
            }
        }
    }

    private function importUsers(array $records): void
    {
        foreach ($records as $record) {
            $existing = null;

            if (! empty($record['id'])) {
                $existing = User::find($record['id']);
            }

            $data = [
                'name' => $record['name'] ?? '',
                'email' => $record['email'] ?? '',
                'is_admin' => $record['is_admin'] ?? false,
            ];

            if ($existing) {
                $existing->update($data);
            } else {
                User::create($data);
            }
        }
    }

    private function unflattenFromCsv(array $row): array
    {
        $result = [];

        foreach ($row as $key => $value) {
            if ($value === '' || $value === null) {
                $result[$key] = null;
            } elseif (in_array($key, self::BOOL_FIELDS, true)) {
                $result[$key] = $value === '1';
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public function render()
    {
        return view('settings.table-backup');
    }
}
