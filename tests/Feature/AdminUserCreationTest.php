<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_user_id_one_can_access_admin_user_creation(): void
    {
        $admin = User::factory()->create();
        $this->assertSame(1, $admin->id);

        $this->actingAs($admin)
            ->get(route('admin.users.create'))
            ->assertOk();

        $regularUser = User::factory()->create();
        $this->assertSame(2, $regularUser->id);

        $this->actingAs($regularUser)
            ->get(route('admin.users.create'))
            ->assertForbidden();
    }
}
