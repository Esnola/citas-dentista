<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appointments = [
            ['telefono' => '618287914', 'fecha' => '2026-07-01', 'hora' => '09:30', 'enviado' => true, 'activo' => false],
            ['telefono' => '618287914', 'fecha' => '2026-07-06', 'hora' => '09:30', 'enviado' => false, 'activo' => false],
            ['telefono' => '618287914', 'fecha' => '2026-06-02', 'hora' => '09:30', 'enviado' => false, 'activo' => true],
            ['telefono' => '618287914', 'fecha' => '2026-07-03', 'hora' => '09:30', 'enviado' => false, 'activo' => true],
            ['telefono' => '618287914', 'fecha' => '2026-07-04', 'hora' => '09:30', 'enviado' => false, 'activo' => true],
            ['telefono' => '618287914', 'fecha' => '2026-07-05', 'hora' => '09:30', 'enviado' => false, 'activo' => true],
            ['telefono' => '618287914', 'fecha' => '2026-07-06', 'hora' => '09:30', 'enviado' => false, 'activo' => true],
            ['telefono' => '618287914', 'fecha' => '2026-07-04', 'hora' => '09:30', 'enviado' => false, 'activo' => true],
            ['telefono' => '618287914', 'fecha' => '2026-07-07', 'hora' => '09:30', 'enviado' => false, 'activo' => true],
            ['telefono' => '618287914', 'fecha' => '2026-07-08', 'hora' => '09:30', 'enviado' => false, 'activo' => true],
            ['telefono' => '600123123', 'fecha' => '2026-07-09', 'hora' => '09:30', 'enviado' => false, 'activo' => true],
            ['telefono' => '618287914', 'fecha' => '2026-07-12', 'hora' => '09:30', 'enviado' => false, 'activo' => true],
            ['telefono' => '611234567', 'fecha' => '2026-07-01', 'hora' => '10:15', 'enviado' => false, 'activo' => true],
            ['telefono' => '622345678', 'fecha' => '2026-07-02', 'hora' => '11:00', 'enviado' => true, 'activo' => false],
            ['telefono' => '633456789', 'fecha' => '2026-07-02', 'hora' => '12:30', 'enviado' => false, 'activo' => true],
            ['telefono' => '633456789', 'fecha' => '2026-07-02', 'hora' => '12:30', 'enviado' => false, 'activo' => true],
            ['telefono' => '633456789', 'fecha' => '2026-07-02', 'hora' => '12:30', 'enviado' => false, 'activo' => true],
            ['telefono' => '633456789', 'fecha' => '2026-07-02', 'hora' => '12:30', 'enviado' => false, 'activo' => true],
            ['telefono' => '644567890', 'fecha' => '2026-07-03', 'hora' => '09:00', 'enviado' => true, 'activo' => false],
            ['telefono' => '655678901', 'fecha' => '2026-07-03', 'hora' => '13:15', 'enviado' => false, 'activo' => false],
            ['telefono' => '655678901', 'fecha' => '2026-07-03', 'hora' => '13:15', 'enviado' => false, 'activo' => false],
            ['telefono' => '655678901', 'fecha' => '2026-07-03', 'hora' => '13:15', 'enviado' => false, 'activo' => false],
            ['telefono' => '655678901', 'fecha' => '2026-07-03', 'hora' => '13:15', 'enviado' => false, 'activo' => false],
            ['telefono' => '666789012', 'fecha' => '2026-07-06', 'hora' => '16:00', 'enviado' => false, 'activo' => true],
            ['telefono' => '677890123', 'fecha' => '2026-07-06', 'hora' => '17:30', 'enviado' => false, 'activo' => true],
            ['telefono' => '677890123', 'fecha' => '2026-07-06', 'hora' => '17:30', 'enviado' => false, 'activo' => true],
            ['telefono' => '688901234', 'fecha' => '2026-07-07', 'hora' => '10:45', 'enviado' => true, 'activo' => false],
            ['telefono' => '699012345', 'fecha' => '2026-07-07', 'hora' => '18:00', 'enviado' => false, 'activo' => false],
            ['telefono' => '618287914', 'fecha' => '2026-07-08', 'hora' => '12:00', 'enviado' => false, 'activo' => true],
            ['telefono' => '618287914', 'fecha' => '2026-07-08', 'hora' => '12:00', 'enviado' => false, 'activo' => true],
            ['telefono' => '618287914', 'fecha' => '2026-07-08', 'hora' => '12:00', 'enviado' => false, 'activo' => true],
            ['telefono' => '618287914', 'fecha' => '2026-07-08', 'hora' => '12:00', 'enviado' => false, 'activo' => true],
            ['telefono' => '644567890', 'fecha' => '2026-07-09', 'hora' => '15:30', 'enviado' => false, 'activo' => true],
        ];

        foreach ($appointments as $appointment) {
            $client = Client::query()
                ->where('telefono', Client::normalizePhone($appointment['telefono']))
                ->first();

            if (! $client) {
                continue;
            }

            Appointment::query()->updateOrCreate(
                [
                    'client_id' => $client->id,
                    'fecha' => Carbon::parse($appointment['fecha'])->startOfDay()->toDateTimeString(),
                    'hora' => $appointment['hora'],
                ],
                [
                    'enviado' => $appointment['enviado'],
                    'activo' => $appointment['activo'],
                ]
            );
        }
    }
}
