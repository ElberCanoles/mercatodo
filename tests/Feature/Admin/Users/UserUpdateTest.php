<?php

namespace Tests\Feature\Admin\Users;

use App\Contracts\Repository\User\UserWriteRepositoryInterface;
use App\Domain\Users\Enums\RoleType;
use App\Domain\Users\Enums\UserStatus;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
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

        $this->assertSame(expected: 'New Name', actual: $this->buyer->name);
        $this->assertSame(expected: 'New Last Name', actual: $this->buyer->last_name);
        $this->assertSame(expected: UserStatus::INACTIVE, actual: $this->buyer->status);
    }

    public function test_admin_can_not_update_users_when_internal_error(): void
    {
        $this->mock(abstract: UserWriteRepositoryInterface::class, mock: function ($mock) {
            $mock->shouldReceive('update')->andReturn(false);
        });

        $response = $this
            ->actingAs($this->admin)
            ->put(route(name: 'admin.users.update', parameters: ['user' => $this->buyer->id]), [
                'name' => 'New Name',
                'last_name' => 'New Last Name',
                'status' => UserStatus::INACTIVE,
            ]);

        $response->assertStatus(status: 500);

        Mockery::close();
    }
}
