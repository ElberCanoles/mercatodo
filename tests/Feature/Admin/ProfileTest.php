<?php

namespace Tests\Feature\Admin;

use App\Enums\User\RoleType;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_admin_page_is_displayed(): void
    {
        Role::create(['name' => RoleType::Administrator]);

        $user = User::factory()
            ->create()
            ->assignRole(RoleType::Administrator);

        $response = $this
            ->actingAs($user)
            ->get('/admin/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        Role::create(['name' => RoleType::Administrator]);

        $user = User::factory()
            ->create()
            ->assignRole(RoleType::Administrator);

        $response = $this
            ->actingAs($user)
            ->patch('/admin/profile', [
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

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        Role::create(['name' => RoleType::Administrator]);

        $user = User::factory()
            ->create()
            ->assignRole(RoleType::Administrator);

        $response = $this
            ->actingAs($user)
            ->patch('/admin/profile', [
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
