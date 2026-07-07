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
            '09:15',
            '09:30',
            '09:45',
            '10:00',
            '10:15',
            '10:30',
            '10:45',
            '11:00',
            '11:15',
            '11:30',
            '11:45',
            '12:00',
            '12:15',
            '12:30',
            '12:45',
            '13:00',
            '13:15',
            '13:30',
            '13:45',
            '14:00',
            '14:15',
            '14:30',
            '14:45',
            '15:00',
            '15:15',
            '15:30',
            '15:45',
            '16:00',
        ];

        $clientList = Client::query()->orderBy('id')->get();

        if ($clientList->isEmpty()) {
            return;
        }

        Appointment::query()->delete();

        $appointmentIndex = 0;

        foreach ($dates as $date) {
            $dailyAppointmentIndex = 0;

            foreach ($timeSlots as $time) {
                for ($slotAppointment = 0; $slotAppointment < 2; $slotAppointment++) {
                    $client = $clientList[$dailyAppointmentIndex % $clientList->count()];

                    Appointment::query()->create([
                        'client_id' => $client->id,
                        'fecha' => $date,
                        'hora' => $time,
                        'enviado' => false,
                        'entregado' => false,
                        'activo' => $appointmentIndex % 11 !== 0,
                        'cita_activa' => $appointmentIndex % 12 !== 0,
                    ]);

                    $appointmentIndex++;
                    $dailyAppointmentIndex++;
                }
            }
        }
    }
}
