<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginShowFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_user_can_access_to_login_form(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
}
