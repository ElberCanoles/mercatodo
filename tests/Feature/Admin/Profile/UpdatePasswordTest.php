<?php

namespace Tests\Feature\Admin\Profile;

use App\Contracts\Repository\User\UserWriteRepositoryInterface;
use App\Enums\User\RoleType;
use App\Models\User;
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
        $this->seed(class: RoleSeeder::class);
    }

    public function test_admin_password_can_be_updated(): void
    {
        $user = User::factory()->create()->assignRole(RoleType::ADMINISTRATOR);

        $response = $this
            ->actingAs($user)
            ->patch(uri: '/admin/profile/password', data: [
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

        $this->mock(abstract: UserWriteRepositoryInterface::class, mock: function ($mock) {
            $mock->shouldReceive('updatePassword')->andReturn(false);
        });

        $response = $this
            ->actingAs($user)
            ->patch(uri: '/admin/profile/password', data: [
                'current_password' => 'password',
                'password' => 'new_password',
                'password_confirmation' => 'new_password',
            ]);

        $response->assertStatus(status: 500);

        Mockery::close();
    }
}
