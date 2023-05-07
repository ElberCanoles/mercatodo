<?php

namespace Tests\Feature\Admin\Profile;

use App\Contracts\Repository\User\UserWriteRepositoryInterface;
use App\Enums\User\RoleType;
use App\Models\User;
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
        $this->seed(class: RoleSeeder::class);
    }

    public function test_admin_profile_page_is_displayed(): void
    {
        $user = User::factory()
            ->create()
            ->assignRole(RoleType::ADMINISTRATOR);

        $response = $this
            ->actingAs($user)
            ->get(uri: '/admin/profile');

        $response->assertOk();
    }

    public function test_admin_profile_information_can_be_updated(): void
    {
        $user = User::factory()
            ->create()
            ->assignRole(RoleType::ADMINISTRATOR);

        $response = $this
            ->actingAs($user)
            ->patch(uri: '/admin/profile', data: [
                'name' => 'Test User',
                'last_name' => 'User Test',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        $user->refresh();

        $this->assertSame(expected: 'Test User', actual: $user->name);
        $this->assertSame(expected: 'test@example.com', actual: $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_admin_profile_can_not_update_when_internal_error(): void
    {
        $user = User::factory()
            ->create()
            ->assignRole(RoleType::ADMINISTRATOR);

        $this->mock(abstract: UserWriteRepositoryInterface::class, mock: function ($mock) {
            $mock->shouldReceive('update')->andReturn(false);
        });

        $response = $this
            ->actingAs($user)
            ->patch(uri: '/admin/profile', data: [
                'name' => 'Test User',
                'last_name' => 'User Test',
                'email' => 'test@example.com',
            ]);

        $response->assertStatus(status: 500);

        Mockery::close();
    }

    public function test_admin_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()
            ->create()
            ->assignRole(RoleType::ADMINISTRATOR);

        $response = $this
            ->actingAs($user)
            ->patch(uri: '/admin/profile', data: [
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
