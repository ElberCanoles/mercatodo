<?php

namespace Tests\Feature\Auth;

use App\Contracts\Repository\User\UserWriteRepositoryInterface;
use App\Models\User;
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

    public function test_guest_user_can_not_send_register_when_internal_error(): void
    {
        $data = [
            'name' => 'Juan Pablo',
            'last_name' => 'Gonzales Lopez',
            'email' => 'juanpablo@mercatodo.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->mock(UserWriteRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('store')->andReturn(null);
        });

        $response = $this->post('/register', $data);
        $response->assertStatus(500);

        Mockery::close();
    }
}
