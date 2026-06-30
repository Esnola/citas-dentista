<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AppointmentsExport;
use App\Exports\ClientsExport;
use Illuminate\Routing\Controller;

class ExportController extends Controller
{
    public function appointments()
    {
        $export = new AppointmentsExport;

        return $this->downloadCsv(
            $export->headings(),
            $export->collection()->map(fn ($row) => $export->map($row))->all(),
            'citas.csv'
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
}
