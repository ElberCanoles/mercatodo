<?php

namespace Tests\Feature\Auth;

use App\Enums\User\RoleType;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_authenticated_admin_users_can_logout(): void
    {
        $user = User::factory()->create()->assignRole(RoleType::ADMINISTRATOR);

        Auth::login($user);

        $response = $this->post('/logout');

        $response->assertRedirect(route('home'));

        $this->assertGuest();
    }

    public function test_authenticated_buyer_users_can_logout(): void
    {
        $user = User::factory()->create()->assignRole(RoleType::BUYER);

        Auth::login($user);

        $response = $this->post('/logout');

        $response->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
