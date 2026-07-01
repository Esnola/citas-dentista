<?php

namespace App\Imports;

use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class WhatsAppMessagesImport implements ToCollection, WithCustomCsvSettings, WithHeadingRow
{
    use Importable;

    private array $previewRows = [];

    public function __construct(
        private readonly ?User $user = null,
        private readonly string $templateKey = '',
        private readonly bool $persist = true,
        private readonly string $csvDelimiter = ','
    ) {}

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $preparedRow = $this->prepareRow(is_array($row) ? $row : $row->toArray());

            if ($this->persist) {
                $client = Client::upsertFromImport($preparedRow);

                WhatsAppMessage::create([
                    'user_id' => $this->user?->id,
                    'client_id' => $client->id,
                    'nombre' => $preparedRow['nombre'],
                    'apellidos' => $preparedRow['apellidos'],
                    'telefono' => $preparedRow['telefono'],
                    'scheduled_for' => $preparedRow['scheduled_for'],
                    'message' => $preparedRow['message'],
                    'source' => WhatsAppMessage::SOURCE_CSV,
                    'status' => WhatsAppMessage::STATUS_PENDING,
                    'metadata' => [
                        'imported_from' => 'csv',
                        'template_key' => $preparedRow['template_key'],
                    ],
                ]);

                continue;
            }

            $this->previewRows[] = $preparedRow;
        }
    }

    public function previewRows(): array
    {
        return $this->previewRows;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => $this->csvDelimiter,
            'enclosure' => '"',
            'escape_character' => '\\',
            'contiguous' => false,
            'input_encoding' => 'UTF-8',
        ];
    }

    private function normalizeRow(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            $normalized[$this->normalizeKey((string) $key)] = $value;
        }

        return $normalized;
    }

    private function extractValue(array $row, array $aliases): mixed
    {
        foreach ($aliases as $alias) {
            $normalizedAlias = $this->normalizeKey($alias);

            if (array_key_exists($normalizedAlias, $row)) {
                return $row[$normalizedAlias];
            }
        }

        return null;
    }

    private function normalizeKey(string $key): string
    {
        $key = Str::ascii(trim($key));
        $key = strtolower($key);
        $key = preg_replace('/[^a-z0-9]+/', '_', $key) ?? $key;

        return trim($key, '_');
    }

    private function prepareRow(array $row): array
    {
        $normalized = $this->normalizeRow($row);
        $scheduledDate = $this->extractValue($normalized, ['fecha', 'scheduled_date', 'fecha_cita', 'fecha_de_cita', 'dia']);
        $scheduledTime = $this->extractValue($normalized, ['hora', 'scheduled_time', 'hora_cita']);
        $scheduledFor = $this->combineDateAndTime($scheduledDate, $scheduledTime);
        $templateKey = $this->extractValue($normalized, ['plantilla', 'template', 'template_key']) ?: $this->templateKey;
        $errors = [];

        $messageData = [
            'nombre' => $this->extractValue($normalized, ['nombre', 'nombre_completo', 'nombres']),
            'apellidos' => $this->extractValue($normalized, ['apellidos', 'apellido', 'apellidos_del_paciente']),
            'telefono' => $this->extractValue($normalized, ['telefono', 'teléfono', 'numero', 'numero_telefono', 'telefono_movil', 'whatsapp_number']),
            'scheduled_for' => $scheduledFor,
        ];

        foreach (['nombre', 'apellidos', 'telefono'] as $field) {
            if (trim((string) $messageData[$field]) === '') {
                $errors[$field] = 'El campo '.$field.' es obligatorio.';
            }
        }

        if ($scheduledDate === null) {
            $errors['fecha'] = 'La fecha es obligatoria.';
        }

        if ($scheduledTime === null) {
            $errors['hora'] = 'La hora es obligatoria.';
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        return [
            'nombre' => $messageData['nombre'],
            'apellidos' => $messageData['apellidos'],
            'telefono' => $messageData['telefono'],
            'scheduled_for' => $scheduledFor->toDateTimeString(),
            'scheduled_date' => $scheduledFor->toDateString(),
            'scheduled_time' => $scheduledFor->format('H:i'),
            'template_key' => $templateKey ?: config('whatsapp.default_template'),
            'message' => WhatsAppMessage::buildMessage($messageData, $templateKey),
        ];
    }

    private function combineDateAndTime(mixed $dateValue, mixed $timeValue): Carbon
    {
        return Carbon::parse(
            $this->normalizeDate($dateValue)->format('Y-m-d').' '.$this->normalizeTime($timeValue)->format('H:i:s')
        );
    }

    private function normalizeDate(mixed $value): Carbon
    {
        if (is_numeric($value)) {
            return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value));
        }

        $value = trim((string) $value);

        foreach (['d/m/Y', 'd-m-Y', 'd.m.Y', 'Y-m-d'] as $format) {
            try {
                return Carbon::createFromFormat($format, $value);
            } catch (\Throwable) {
                // Try the next common CSV format.
            }
        }

        return Carbon::parse($value);
    }

    private function normalizeTime(mixed $value): Carbon
    {
        if (is_numeric($value)) {
            return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value));
        }

        return Carbon::parse((string) $value);
    }
}
