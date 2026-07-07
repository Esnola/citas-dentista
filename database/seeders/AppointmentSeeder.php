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
            ->map(fn (int $offset): string => now()->addDays($offset)->toDateString())
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

        $pastDates = [
            now()->subDays(2)->toDateString(),
            now()->subDay()->toDateString(),
        ];

        $pastSlots = ['09:00', '10:00'];
        $appointmentIndex = 0;

        foreach ($pastDates as $date) {
            foreach ($clientList as $client) {
                for ($i = 0; $i < 2; $i++) {
                    Appointment::query()->create([
                        'client_id' => $client->id,
                        'fecha' => $date,
                        'hora' => $pastSlots[$i],
                        'enviado' => true,
                        'entregado' => true,
                        'activo' => $appointmentIndex % 11 !== 0,
                        'cita_activa' => $appointmentIndex % 12 !== 0,
                    ]);

                    $appointmentIndex++;
                }
            }
        }

        $maxPerDay = 2;
        $appointmentIndex = 0;

        foreach ($dates as $date) {
            $slotIndex = 0;

            foreach ($clientList as $client) {
                $appointmentsForClient = min($maxPerDay, count($timeSlots) - $slotIndex);

                for ($i = 0; $i < $appointmentsForClient; $i++) {
                    Appointment::query()->create([
                        'client_id' => $client->id,
                        'fecha' => $date,
                        'hora' => $timeSlots[$slotIndex],
                        'enviado' => false,
                        'entregado' => false,
                        'activo' => $appointmentIndex % 11 !== 0,
                        'cita_activa' => $appointmentIndex % 12 !== 0,
                    ]);

                    $appointmentIndex++;
                    $slotIndex++;
                }
            }
        }
    }
}
