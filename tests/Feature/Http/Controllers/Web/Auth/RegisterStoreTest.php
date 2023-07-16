<?php

namespace Tests\Feature\Http\Controllers\Web\Auth;

use App\Domain\Users\Services\EntryPoint;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterStoreTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_guest_user_can_send_register_data(): void
    {
        $data = [
            'name' => 'Juan Pablo',
            'last_name' => 'Gonzales Lopez',
            'email' => 'juanpablo@mercatodo.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post('/register', $data);

        $response->assertSessionDoesntHaveErrors()
            ->assertRedirect(EntryPoint::resolveRedirectRoute());

        $this->assertDatabaseCount('users', 1);

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
        ]);
    }
}
