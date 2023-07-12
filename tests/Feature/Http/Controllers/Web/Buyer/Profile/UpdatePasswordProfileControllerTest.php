<?php

namespace Tests\Feature\Http\Controllers\Web\Buyer\Profile;

use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdatePasswordProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
    }

    public function test_buyer_password_can_be_updated(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $user->assignRole(role: Roles::BUYER);

        $response = $this
            ->actingAs($user)
            ->patch(uri: '/buyer/profile/password', data: [
                'current_password' => 'password',
                'password' => 'new_password',
                'password_confirmation' => 'new_password',
            ]);

        $response->assertSessionDoesntHaveErrors()
            ->assertOk();
    }
}
