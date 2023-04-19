<?php

namespace Tests\Feature\Buyer\Profile;

use App\Enums\User\RoleType;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_buyer_profile_page_is_displayed(): void
    {
        $user = User::factory()
            ->create()
            ->assignRole(RoleType::BUYER);

        $response = $this
            ->actingAs($user)
            ->get('/buyer/profile');

        $response->assertOk();
    }

    public function test_buyer_profile_information_can_be_updated(): void
    {
        $user = User::factory()
            ->create()
            ->assignRole(RoleType::BUYER);

        $response = $this
            ->actingAs($user)
            ->patch('/buyer/profile', [
                'name' => 'Test User',
                'last_name' => 'User Test',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_buyer_profile_can_not_update_when_internal_error(): void
    {
        $user = User::factory()
            ->create()
            ->assignRole(RoleType::BUYER);

        $this->mock(UserRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('update')->andReturn(null);
        });

        $response = $this
            ->actingAs($user)
            ->patch('/buyer/profile', [
                'name' => 'Test User',
                'last_name' => 'User Test',
                'email' => 'test@example.com',
            ]);

        $response->assertStatus(500);

        Mockery::close();
    }

    public function test_buyer_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()
            ->create()
            ->assignRole(RoleType::BUYER);

        $response = $this
            ->actingAs($user)
            ->patch('/buyer/profile', [
                'name' => 'Test User',
                'last_name' => 'User Test',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        $this->assertNotNull($user->refresh()->email_verified_at);
    }
}
