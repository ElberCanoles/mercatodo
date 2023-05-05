<?php

namespace Tests\Feature\Buyer\Profile;

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
        $this->seed(RoleSeeder::class);
    }

    public function test_buyer_password_can_be_updated(): void
    {
        $user = User::factory()->create()->assignRole(RoleType::BUYER);

        $response = $this
            ->actingAs($user)
            ->patch('/buyer/profile/password', [
                'current_password' => 'password',
                'password' => 'new_password',
                'password_confirmation' => 'new_password',
            ]);

        $response->assertSessionDoesntHaveErrors()
            ->assertOk();
    }

    public function test_buyer_password_can_not_be_updated_when_internal_error(): void
    {
        $user = User::factory()->create()->assignRole(RoleType::BUYER);

        $this->mock(UserWriteRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('updatePassword')->andReturn(false);
        });

        $response = $this
            ->actingAs($user)
            ->patch('/buyer/profile/password', [
                'current_password' => 'password',
                'password' => 'new_password',
                'password_confirmation' => 'new_password',
            ]);

        $response->assertStatus(500);

        Mockery::close();
    }
}
