<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Client;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dates = collect(range(0, 10))
            ->map(fn (int $offset) => now()->addDays($offset))
            ->reject(fn ($date) => $date->isSunday())
            ->map(fn ($date): string => $date->toDateString())
            ->all();

        $timeSlots = [
            '09:00',
            '09:30',
            '10:00',
            '10:30',
            '11:00',
            '11:30',
            '12:00',
            '12:30',
            '13:00',
            '13:30',
            '14:00',
            '14:30',
            '15:00',
            '15:30',
            '16:00',
        ];

        $clientList = Client::query()->orderBy('id')->get();

        if ($clientList->isEmpty()) {
            return;
        }

        Appointment::query()->delete();

        $pastDates = collect([
            now()->subDays(2),
            now()->subDay(),
        ])
            ->reject(fn ($date) => $date->isSunday())
            ->map(fn ($date): string => $date->toDateString())
            ->all();

        $appointmentIndex = 0;

        foreach ($pastDates as $date) {
            foreach ($clientList as $index => $client) {
                Appointment::query()->create([
                    'client_id' => $client->id,
                    'fecha' => $date,
                    'hora' => $timeSlots[$index % count($timeSlots)],
                    'enviado' => true,
                    'entregado' => true,
                    'activo' => $appointmentIndex % 11 !== 0,
                    'cita_activa' => $appointmentIndex % 12 !== 0,
                ]);

                $appointmentIndex++;
            }
        }

        $appointmentIndex = 0;

        foreach ($dates as $date) {
            foreach ($clientList as $index => $client) {
                Appointment::query()->create([
                    'client_id' => $client->id,
                    'fecha' => $date,
                    'hora' => $timeSlots[$index % count($timeSlots)],
                    'enviado' => false,
                    'entregado' => false,
                    'activo' => $appointmentIndex % 11 !== 0,
                    'cita_activa' => $appointmentIndex % 12 !== 0,
                ]);

                $appointmentIndex++;
            }
        }
    }
}
