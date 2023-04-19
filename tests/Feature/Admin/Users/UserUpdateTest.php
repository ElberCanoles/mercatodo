<?php

namespace Tests\Feature\Admin\Users;

use App\Enums\User\RoleType;
use App\Enums\User\UserStatus;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class UserUpdateTest extends TestCase
{

    use RefreshDatabase;

    private User $admin;
    private User $buyer;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->admin = User::factory()->create()->assignRole(RoleType::ADMINISTRATOR);
        $this->buyer = User::factory()->create()->assignRole(RoleType::BUYER);
    }

    public function test_admin_can_update_users(): void
    {

        $response = $this
            ->actingAs($this->admin)
            ->put(route('admin.users.update', ['user' => $this->buyer->id]), [
                'name' => 'New Name',
                'last_name' => 'New Last Name',
                'status' => UserStatus::INACTIVE,
            ]);

        $response->assertOk()
            ->assertSessionDoesntHaveErrors();

        $this->buyer->refresh();

        $this->assertSame('New Name', $this->buyer->name);
        $this->assertSame('New Last Name', $this->buyer->last_name);
        $this->assertSame(UserStatus::INACTIVE, $this->buyer->status);
    }

    public function test_admin_can_not_update_users_when_internal_error(): void
    {

        $this->mock(UserRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('update')->andReturn(null);
        });

        $response = $this
            ->actingAs($this->admin)
            ->put(route('admin.users.update', ['user' => $this->buyer->id]), [
                'name' => 'New Name',
                'last_name' => 'New Last Name',
                'status' => UserStatus::INACTIVE,
            ]);

        $response->assertStatus(500);

        Mockery::close();
    }
}
