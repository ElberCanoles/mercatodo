<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\Auth\EntryPoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationNotificationStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_unverified_can_send_email_verification(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->post(route('verification.send'));

        $response->assertRedirect(session()->previousUrl())
            ->assertSessionHas('status', 'verification-link-sent');
    }

    public function test_user_verified_are_redirect_to_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('verification.send'));

        $response->assertRedirect(EntryPoint::resolveRedirectRoute());
    }
}
