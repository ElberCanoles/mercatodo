<?php

namespace Tests\Feature\Auth;

use App\Enums\User\RoleType;
use App\Services\Auth\EntryPoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {

        Role::create(['name' => RoleType::ADMINISTRATOR]);
        Role::create(['name' => RoleType::BUYER]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'last_name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(EntryPoint::resolveRedirectRoute());
    }
}
