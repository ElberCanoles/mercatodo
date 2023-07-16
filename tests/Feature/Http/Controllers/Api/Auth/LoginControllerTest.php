<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_guest_user_can_login_with_correct_credentials(): void
    {
        $this->postJson(route(name: 'api.login'), [
            'email' => $this->user->email,
            'password' => 'password'
        ])
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has(key: 'access_token');
            });
    }

    public function test_guest_user_can_not_login_with_wrong_credentials(): void
    {
        $this->postJson(route(name: 'api.login'), [
            'email' => $this->user->email,
            'password' => 'wrong-password'
        ])
            ->assertUnprocessable()
            ->assertJson(function (AssertableJson $json) {
                $json->where(key: 'message', expected: trans(key: 'auth.failed'));
            });
    }
}
