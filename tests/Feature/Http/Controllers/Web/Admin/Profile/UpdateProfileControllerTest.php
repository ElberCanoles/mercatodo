<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Profile;

use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
    }

    public function test_admin_profile_page_is_displayed(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();

        $user->assignRole(role: Roles::ADMINISTRATOR);

        $response = $this
            ->actingAs($user)
            ->get(uri: '/admin/profile');

        $response->assertOk();
    }

    public function test_admin_profile_information_can_be_updated(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();

        $user->assignRole(role: Roles::ADMINISTRATOR);

        $response = $this
            ->actingAs($user)
            ->patch(uri: '/admin/profile', data: [
                'name' => 'Test user',
                'last_name' => 'User test',
                'email' => 'test@example.com'
            ]);

        $response->assertSessionHasNoErrors()
            ->assertOk();

        $user->refresh();

        $this->assertSame(expected: 'Test user', actual: $user->name);
        $this->assertSame(expected: 'User test', actual: $user->last_name);
        $this->assertSame(expected: 'test@example.com', actual: $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_admin_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();

        $user->assignRole(role: Roles::ADMINISTRATOR);

        $response = $this
            ->actingAs($user)
            ->patch(uri: '/admin/profile', data: [
                'name' => 'Test User',
                'last_name' => 'User Test',
                'email' => $user->email
            ]);

        $response->assertSessionHasNoErrors()
            ->assertOk();

        $this->assertNotNull($user->refresh()->email_verified_at);
    }
}
