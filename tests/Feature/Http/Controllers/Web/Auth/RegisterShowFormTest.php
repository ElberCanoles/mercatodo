<?php

namespace Tests\Feature\Http\Controllers\Web\Auth;

use Tests\TestCase;

class RegisterShowFormTest extends TestCase
{
    public function test_guest_user_can_access_to_register_form(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk()
            ->assertViewIs('auth.register');
    }
}
