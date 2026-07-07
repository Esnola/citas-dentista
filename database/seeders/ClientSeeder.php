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
            ['nombre' => 'Juan Jose', 'apellidos' => 'Gonzalez Vega', 'telefono' => '618287914'],
            ['nombre' => 'Esther', 'apellidos' => 'Amado Calviño', 'telefono' => '659366775'],
            ['nombre' => 'María', 'apellidos' => 'López García', 'telefono' => '612345678'],
            ['nombre' => 'Carlos', 'apellidos' => 'Ruiz Martín', 'telefono' => '623456789'],
            ['nombre' => 'Laura', 'apellidos' => 'Fernández Sánchez', 'telefono' => '634567890'],
            ['nombre' => 'Pedro', 'apellidos' => 'Díaz Romero', 'telefono' => '645678901'],
            ['nombre' => 'Ana', 'apellidos' => 'Moreno Torres', 'telefono' => '656789012'],
            ['nombre' => 'Miguel', 'apellidos' => 'Jiménez Vargas', 'telefono' => '667890123'],
            ['nombre' => 'Sofía', 'apellidos' => 'Hernández Flores', 'telefono' => '678901234'],
            ['nombre' => 'Diego', 'apellidos' => 'Castillo Navarro', 'telefono' => '689012345'],
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
