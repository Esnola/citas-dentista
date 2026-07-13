<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AppointmentsExport;
use App\Exports\ClientsExport;
use App\Exports\UsersExport;
use App\Models\Appointment;
use App\Models\AppointmentChange;
use App\Models\AppointmentReminderPreference;
use App\Models\AppSetting;
use App\Models\Client;
use App\Models\TwilioContentTemplate;
use App\Models\User;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppSenderNumber;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use PDO;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

class ExportController extends Controller
{
    public function settings()
    {
        $data = [
            'version' => 2,
            'exported_at' => now()->toIso8601String(),
            'settings' => $this->gatherSettingsData(),
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $filename = 'settings-backup-'.now()->format('Y-m-d-His').'.json';

        $path = storage_path("app/{$filename}");
        file_put_contents($path, $json);

        return response()
            ->download($path, $filename, ['Content-Type' => 'application/json'])
            ->deleteFileAfterSend(true);
    }

    public function settingsCsv()
    {
        abort_unless(class_exists(ZipArchive::class), 500, 'ZipArchive no está disponible.');

        $data = $this->gatherSettingsData();
        $zipPath = tempnam(sys_get_temp_dir(), 'settings-csv-');
        abort_if($zipPath === false, 500, 'No se pudo crear el archivo temporal.');

        $zip = new ZipArchive;
        abort_if($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true, 500, 'No se pudo crear el ZIP.');

        foreach ($data as $table => $rows) {
            if ($rows === null) {
                $rows = [];
            }

            if (! is_array($rows)) {
                continue;
            }

            // Single-row settings stored as associative array
            if (isset($rows['id']) || array_is_list($rows) === false) {
                $rows = [$rows];
            }

            $flatRows = array_map(fn (array $row) => $this->flattenForCsv($row), $rows);

            $headings = $flatRows !== [] ? array_keys($flatRows[0]) : [];

            $csvContent = $this->buildCsvContent($headings, $flatRows);
            $zip->addFromString("{$table}.csv", $csvContent);
        }

        $zip->close();

        $filename = 'settings-csv-'.now()->format('Y-m-d-His').'.zip';

        return response()
            ->download($zipPath, $filename, ['Content-Type' => 'application/zip'])
            ->deleteFileAfterSend(true);
    }

    private function gatherSettingsData(): array
    {
        $model = AppSetting::query()->first();

        return [
            'app_settings' => $model?->only(['id', 'retention_period', 'dispatch_enabled', 'dispatch_hours', 'twilio_template_appointment_reminder_id', 'twilio_template_appointment_created_id']),
            'appointment_reminder_preferences' => AppointmentReminderPreference::query()
                ->select(['id', 'channel', 'lead_days', 'enabled'])
                ->get()
                ->toArray(),
            'whatsapp_credentials' => $this->gatherCredentials(),
            'whatsapp_sender_numbers' => WhatsAppSenderNumber::query()
                ->select(['id', 'whatsapp_credential_id', 'name', 'prefix', 'number', 'selected'])
                ->get()
                ->toArray(),
            'twilio_content_templates' => TwilioContentTemplate::query()
                ->select(['id', 'nombre', 'content_sid', 'content_variables'])
                ->get()
                ->toArray(),
        ];
    }

    private function flattenForCsv(array $row): array
    {
        $flat = [];

        foreach ($row as $key => $value) {
            if (is_array($value)) {
                $flat[$key] = json_encode($value, JSON_UNESCAPED_UNICODE);
            } elseif (is_bool($value)) {
                $flat[$key] = $value ? '1' : '0';
            } else {
                $flat[$key] = $value;
            }
        }

        return $flat;
    }

    private function buildCsvContent(array $headings, array $rows): string
    {
        $output = fopen('php://temp', 'r+');
        fputcsv($output, $headings, ',');

        foreach ($rows as $row) {
            fputcsv($output, $row, ',');
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }

    public function allJson()
    {
        $data = [
            'version' => 1,
            'exported_at' => now()->toIso8601String(),
            'tables' => $this->gatherAllData(),
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $filename = 'database-backup-'.now()->format('Y-m-d-His').'.json';

        $path = storage_path("app/{$filename}");
        file_put_contents($path, $json);

        return response()
            ->download($path, $filename, ['Content-Type' => 'application/json'])
            ->deleteFileAfterSend(true);
    }

    public function allCsv()
    {
        abort_unless(class_exists(ZipArchive::class), 500, 'ZipArchive no está disponible.');

        $data = $this->gatherAllData();
        $zipPath = tempnam(sys_get_temp_dir(), 'db-csv-');
        abort_if($zipPath === false, 500, 'No se pudo crear el archivo temporal.');

        $zip = new ZipArchive;
        abort_if($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true, 500, 'No se pudo crear el ZIP.');

        foreach ($data as $table => $rows) {
            if (empty($rows)) {
                continue;
            }

            $flatRows = array_map(fn (array $row) => $this->flattenForCsv($row), $rows);
            $headings = array_keys($flatRows[0]);
            $csvContent = $this->buildCsvContent($headings, $flatRows);
            $zip->addFromString("{$table}.csv", $csvContent);
        }

        $zip->close();

        $filename = 'database-csv-'.now()->format('Y-m-d-His').'.zip';

        return response()
            ->download($zipPath, $filename, ['Content-Type' => 'application/zip'])
            ->deleteFileAfterSend(true);
    }

    private function gatherAllData(): array
    {
        return [
            'users' => User::query()
                ->select(['id', 'name', 'email', 'is_admin', 'created_at', 'updated_at'])
                ->get()
                ->toArray(),
            'clients' => Client::query()
                ->select(['id', 'nombre', 'apellidos', 'telefono', 'created_at', 'updated_at'])
                ->get()
                ->toArray(),
            'appointments' => Appointment::query()
                ->select([
                    'id', 'client_id', 'fecha', 'hora',
                    'fecha_original', 'hora_original',
                    'enviado', 'entregado',
                    'whatsapp_sent_at', 'whatsapp_delivered_at', 'whatsapp_read_at',
                    'activo', 'cita_activa', 'confirmada', 'pendiente_reprogramacion',
                    'created_at', 'updated_at',
                ])
                ->get()
                ->toArray(),
            'appointment_changes' => AppointmentChange::query()
                ->select(['id', 'appointment_id', 'fecha_anterior', 'hora_anterior', 'fecha_nueva', 'hora_nueva', 'created_at'])
                ->get()
                ->toArray(),
            'whatsapp_messages' => WhatsAppMessage::query()
                ->get()
                ->map(fn (WhatsAppMessage $msg) => [
                    'id' => $msg->id,
                    'client_id' => $msg->client_id,
                    'appointment_id' => $msg->appointment_id,
                    'user_id' => $msg->user_id,
                    'nombre' => $msg->nombre,
                    'apellidos' => $msg->apellidos,
                    'telefono' => $msg->telefono,
                    'message' => $msg->message,
                    'source' => $msg->source,
                    'direction' => $msg->direction,
                    'status' => $msg->status,
                    'scheduled_for' => $msg->scheduled_for,
                    'sent_at' => $msg->sent_at,
                    'delivered_at' => $msg->delivered_at,
                    'read_at' => $msg->read_at,
                    'response' => $msg->response,
                    'responded_at' => $msg->responded_at,
                    'provider_message_id' => $msg->provider_message_id,
                    'last_error' => $msg->last_error,
                    'provider_payload' => $msg->provider_payload ? json_encode($msg->provider_payload) : null,
                    'metadata' => $msg->metadata ? json_encode($msg->metadata) : null,
                    'respuesta' => $msg->respuesta ?? null,
                    'delivery_status' => $msg->delivery_status ?? null,
                    'created_at' => $msg->created_at,
                    'updated_at' => $msg->updated_at,
                ])
                ->toArray(),
            'app_settings' => AppSetting::query()
                ->select(['id', 'retention_period', 'dispatch_enabled', 'dispatch_hours', 'twilio_template_appointment_reminder_id', 'twilio_template_appointment_created_id', 'created_at', 'updated_at'])
                ->get()
                ->toArray(),
            'appointment_reminder_preferences' => AppointmentReminderPreference::query()
                ->select(['id', 'channel', 'lead_days', 'enabled'])
                ->get()
                ->toArray(),
            'whatsapp_credentials' => $this->gatherCredentials(),
            'whatsapp_sender_numbers' => WhatsAppSenderNumber::query()
                ->select(['id', 'whatsapp_credential_id', 'name', 'prefix', 'number', 'selected'])
                ->get()
                ->toArray(),
            'twilio_content_templates' => TwilioContentTemplate::query()
                ->select(['id', 'nombre', 'content_sid', 'content_variables'])
                ->get()
                ->toArray(),
        ];
    }

    private function gatherCredentials(): array
    {
        return WhatsAppCredential::query()
            ->get()
            ->map(fn (WhatsAppCredential $credential) => $credential->only([
                'id', 'driver', 'default_country_code', 'message_mode',
                'account_sid', 'auth_token', 'content_sid', 'test_recipient',
                'timeout', 'connect_timeout', 'cloud_api_base_url', 'cloud_api_version',
                'cloud_api_phone_number_id', 'cloud_api_access_token', 'cloud_api_timeout',
                'default_template', 'default_message', 'mode', 'api_key_sid',
                'api_key_secret', 'status_callback_url', 'selected',
            ]))
            ->toArray();
    }

    public function appointments()
    {
        $export = new AppointmentsExport;

        return $this->downloadCsv(
            $export->headings(),
            $export->collection()->map(fn ($row) => $export->map($row))->all(),
            'citas.csv'
        );
    }

    public function appointmentsJson()
    {
        return $this->downloadJson(
            Appointment::query()->get()->toArray(),
            'citas.json'
        );
    }

    public function clients()
    {
        $export = new ClientsExport;

        return $this->downloadCsv(
            $export->headings(),
            $export->collection()->map(fn ($row) => $export->map($row))->all(),
            'clientes.csv'
        );
    }

    public function clientsJson()
    {
        return $this->downloadJson(
            Client::query()->get()->toArray(),
            'clientes.json'
        );
    }

    public function users()
    {
        $export = new UsersExport;

        return $this->downloadCsv(
            $export->headings(),
            $export->collection()->map(fn ($row) => $export->map($row))->all(),
            'usuarios.csv'
        );
    }

    public function usersJson()
    {
        return $this->downloadJson(
            User::query()->select(['id', 'name', 'email', 'is_admin', 'created_at', 'updated_at'])->get()->toArray(),
            'usuarios.json'
        );
    }

    public function database()
    {
        abort_unless(DB::connection()->getDriverName() === 'sqlite', 501, 'La copia SQL solo está disponible para SQLite.');

        abort_unless(class_exists(ZipArchive::class), 500, 'ZipArchive no está disponible en este servidor.');

        $zipPath = tempnam(sys_get_temp_dir(), 'citas-backup-');
        abort_if($zipPath === false, 500, 'No se pudo crear el archivo temporal.');

        $zip = new ZipArchive;
        abort_if($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true, 500, 'No se pudo crear el ZIP.');

        $zip->addFromString('citas-dentista-backup.sql', $this->dumpSqliteDatabase(DB::connection()->getPdo()));
        $zip->close();

        return response()
            ->download($zipPath, 'citas-dentista-backup.zip', ['Content-Type' => 'application/zip'])
            ->deleteFileAfterSend(true);
    }

    private function downloadCsv(array $headings, array $rows, string $fileName)
    {
        $callback = function () use ($headings, $rows) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, $headings, ',');

            foreach ($rows as $row) {
                fputcsv($handle, $row, ',');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    private function downloadJson(array $data, string $fileName): Response
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $path = storage_path("app/{$fileName}");
        file_put_contents($path, $json);

        return response()
            ->download($path, $fileName, ['Content-Type' => 'application/json'])
            ->deleteFileAfterSend(true);
    }

    private function dumpSqliteDatabase(PDO $pdo): string
    {
        $lines = [
            'PRAGMA foreign_keys=OFF;',
            'BEGIN TRANSACTION;',
        ];

        $tables = $pdo->query(
            "SELECT name, sql FROM sqlite_master WHERE type = 'table' AND sql IS NOT NULL AND name NOT LIKE 'sqlite_%' ORDER BY name"
        )->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tables as $table) {
            $lines[] = $table['sql'].';';
            $lines = array_merge($lines, $this->dumpSqliteTableRows($pdo, $table['name']));
        }

        $otherObjects = $pdo->query(
            "SELECT sql FROM sqlite_master WHERE type IN ('index', 'trigger', 'view') AND sql IS NOT NULL AND name NOT LIKE 'sqlite_%' ORDER BY CASE type WHEN 'index' THEN 0 WHEN 'trigger' THEN 1 ELSE 2 END, name"
        )->fetchAll(PDO::FETCH_COLUMN);

        foreach ($otherObjects as $sql) {
            $lines[] = $sql.';';
        }

        $lines[] = 'COMMIT;';

        return implode("\n", $lines)."\n";
    }

    private function dumpSqliteTableRows(PDO $pdo, string $table): array
    {
        $columns = array_column(
            $pdo->query('PRAGMA table_info('.$this->quoteIdentifier($table).')')->fetchAll(PDO::FETCH_ASSOC),
            'name'
        );

        if ($columns === []) {
            return [];
        }

        $statement = $pdo->query('SELECT * FROM '.$this->quoteIdentifier($table));
        $rows = [];

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = 'INSERT INTO '.$this->quoteIdentifier($table)
                .' ('.implode(', ', array_map([$this, 'quoteIdentifier'], $columns)).') VALUES ('
                .implode(', ', array_map(fn (string $column) => $this->quoteSqliteValue($pdo, $row[$column] ?? null), $columns))
                .');';
        }

        return $rows;
    }

    private function quoteSqliteValue(PDO $pdo, mixed $value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        $quoted = $pdo->quote((string) $value);

        return $quoted !== false ? $quoted : "'".str_replace("'", "''", (string) $value)."'";
    }

    private function quoteIdentifier(string $identifier): string
    {
        return '"'.str_replace('"', '""', $identifier).'"';
    }
}
