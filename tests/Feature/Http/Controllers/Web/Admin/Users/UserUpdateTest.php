<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Users;

use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Enums\UserStatus;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $buyer;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole(role: Roles::ADMINISTRATOR);
        $this->buyer = User::factory()->create();
        $this->buyer->assignRole(role: Roles::BUYER);
    }

    public function test_admin_can_update_users(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->put(route('admin.users.update', ['user' => $this->buyer->id]), [
                'name' => 'New name',
                'last_name' => 'New last name',
                'status' => UserStatus::INACTIVE,
            ]);

        $response->assertOk()
            ->assertSessionDoesntHaveErrors();

        $this->buyer->refresh();

        $this->assertSame(expected: 'New name', actual: $this->buyer->name);
        $this->assertSame(expected: 'New last name', actual: $this->buyer->last_name);
        $this->assertSame(expected: UserStatus::INACTIVE, actual: $this->buyer->status);
    }
}
