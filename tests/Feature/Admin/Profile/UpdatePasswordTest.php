<?php

namespace Tests\Feature\Admin\Profile;

use App\Enums\User\RoleType;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_admin_password_can_be_updated(): void
    {
        $user = User::factory()->create()->assignRole(RoleType::ADMINISTRATOR);

        $response = $this
            ->actingAs($user)
            ->patch('/admin/profile/password', [
                'current_password' => 'password',
                'password' => 'new_password',
                'password_confirmation' => 'new_password',
            ]);

        $response->assertSessionDoesntHaveErrors()
            ->assertOk();
    }

    public function test_admin_password_can_not_be_updated_when_internal_error(): void
    {
        $user = User::factory()->create()->assignRole(RoleType::ADMINISTRATOR);

        $this->mock(UserRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('updatePassword')->andReturn(null);
        });

        $response = $this
            ->actingAs($user)
            ->patch('/admin/profile/password', [
                'current_password' => 'password',
                'password' => 'new_password',
                'password_confirmation' => 'new_password',
            ]);

        $response->assertStatus(500);

        Mockery::close();
    }
}