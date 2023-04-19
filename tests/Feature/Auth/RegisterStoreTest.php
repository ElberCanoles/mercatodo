<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Auth\EntryPoint;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
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
        $user = User::factory()->make();

        $data = [
            'name' => $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post('/register', $data);

        $response->assertSessionDoesntHaveErrors()
            ->assertRedirect(EntryPoint::resolveRedirectRoute());

        $this->assertDatabaseCount('users', 1);

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_guest_user_can_not_send_register_when_internal_error(): void
    {
        $user = User::factory()->make();

        $data = [
            'name' => $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->mock(UserRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('store')->andReturn(null);
        });

        $response = $this->post('/register', $data);
        $response->assertStatus(500);

        Mockery::close();
    }
}
