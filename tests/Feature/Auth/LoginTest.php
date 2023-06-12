<?php

namespace Tests\Feature\Auth;

use App\Enums\User\RoleType;
use App\Enums\User\UserStatus;
use App\Models\User;
use App\Services\Auth\EntryPoint;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create()->assignRole(RoleType::BUYER);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $response->assertSessionDoesntHaveErrors()
            ->assertRedirect(EntryPoint::resolveRedirectRoute());
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_not_authenticate_with_inactive_status(): void
    {
        $user = User::factory()->create([
            'status' => UserStatus::INACTIVE
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }
}
