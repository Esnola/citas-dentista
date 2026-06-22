<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (User::query()->doesntExist()) {
            User::query()->create([
                'name' => env('INITIAL_ADMIN_NAME', 'Administrador'),
                'email' => env('INITIAL_ADMIN_EMAIL', 'admin@example.com'),
                'password' => Hash::make(env('INITIAL_ADMIN_PASSWORD', 'ChangeMe123456!')),
            ]);
        }
    }
}
