<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            ['nombre' => 'Eshter', 'apellidos' => 'Amado Calviño', 'telefono' => '659366775'],
            ['nombre' => 'Ana', 'apellidos' => 'Pérez López', 'telefono' => '600123123'],
            ['nombre' => 'Luis', 'apellidos' => 'Gómez Martín', 'telefono' => '611234567'],
            ['nombre' => 'María', 'apellidos' => 'Sánchez Ruiz', 'telefono' => '622345678'],
            ['nombre' => 'Carlos', 'apellidos' => 'Fernández Díaz', 'telefono' => '633456789'],
            ['nombre' => 'Lucía', 'apellidos' => 'Martín Romero', 'telefono' => '644567890'],
            ['nombre' => 'Javier', 'apellidos' => 'Navarro Torres', 'telefono' => '655678901'],
            ['nombre' => 'Elena', 'apellidos' => 'Moreno García', 'telefono' => '666789012'],
            ['nombre' => 'Miguel', 'apellidos' => 'Ortega Molina', 'telefono' => '677890123'],
            ['nombre' => 'Carmen', 'apellidos' => 'Vidal Herrera', 'telefono' => '688901234'],
            ['nombre' => 'David', 'apellidos' => 'Iglesias Castro', 'telefono' => '699012345'],
        ];

        foreach ($clients as $client) {
            Client::query()->updateOrCreate(
                ['telefono' => Client::normalizePhone($client['telefono'])],
                [
                    'nombre' => $client['nombre'],
                    'apellidos' => $client['apellidos'],
                ]
            );
        }
    }
}
