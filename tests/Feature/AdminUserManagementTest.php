<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_a_user(): void
    {
        $admin = User::factory()->create();
        $user = User::factory()->create([
            'name' => 'Before',
            'email' => 'before@example.com',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $user), [
                'name' => 'After',
                'email' => 'after@example.com',
                'password' => '',
                'password_confirmation' => '',
            ])
            ->assertRedirect(route('admin.users.create'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'After',
            'email' => 'after@example.com',
        ]);
    }

    public function test_admin_can_delete_non_admin_users_but_not_the_main_admin(): void
    {
        $admin = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $otherUser))
            ->assertRedirect(route('admin.users.create'));

        $this->assertDatabaseMissing('users', [
            'id' => $otherUser->id,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertStatus(422);
    }
}
