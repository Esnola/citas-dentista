<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_the_initial_admin_as_first_user(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('users', 1);

        $admin = User::query()->firstOrFail();

        $this->assertSame(1, $admin->id);
        $this->assertSame('admin@example.com', $admin->email);
    }
}
