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
        $phones = $this->phones();
        $dates = collect(range(0, 10))
            ->map(fn (int $offset): string => now()->addDays($offset)->toDateString())
            ->all();

        $timeSlots = [
            '09:00:00',
            '09:30:00',
            '10:00:00',
            '10:30:00',
            '11:00:00',
            '11:30:00',
            '12:00:00',
            '12:30:00',
        ];

        $clients = [];

        foreach ($phones as $index => $phone) {
            $clients[$phone] = Client::query()->firstOrCreate(
                ['telefono' => Client::normalizePhone($phone)],
                [
                    'nombre' => 'Paciente '.str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                    'apellidos' => 'Seeder',
                ]
            );
        }

        Appointment::query()
            ->withTrashed()
            ->whereIn('client_id', array_map(fn (Client $client): int => $client->id, $clients))
            ->forceDelete();

        foreach ($phones as $phone) {
            $client = $clients[$phone];
            $phoneDates = $this->rotateDates($dates, crc32($phone) % count($dates));

            foreach ($phoneDates as $dateIndex => $date) {
                $time = $timeSlots[crc32($phone.'|'.$date) % count($timeSlots)];

                Appointment::query()->create([
                    'client_id' => $client->id,
                    'fecha' => Carbon::parse($date)->startOfDay()->toDateString(),
                    'hora' => $time,
                    'enviado' => $dateIndex % 3 === 0,
                    'entregado' => false,
                    'activo' => $dateIndex % 4 !== 0,
                ]);
            }
        }
    }

    /**
     * @return list<string>
     */
    private function phones(): array
    {
        return array_merge(
            [
                '618287914',
                '600123123',
                '611234567',
                '622345678',
                '633456789',
                '644567890',
                '655678901',
                '666789012',
                '677890123',
                '688901234',
                '699012345',
            ],
            collect(range(1, 19))
                ->map(fn (int $index): string => sprintf('611%06d', $index))
                ->all(),
        );
    }

    /**
     * @param  array<int, string>  $dates
     * @return array<int, string>
     */
    private function rotateDates(array $dates, int $offset): array
    {
        $offset = $offset % count($dates);

        return array_values(array_merge(
            array_slice($dates, $offset),
            array_slice($dates, 0, $offset),
        ));
    }
}
