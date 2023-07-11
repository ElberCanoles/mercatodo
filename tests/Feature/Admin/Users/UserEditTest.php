<?php

namespace Tests\Feature\Admin\Users;

use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Enums\UserStatus;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserEditTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $buyer;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole(role: Roles::ADMINISTRATOR);
        $this->buyer = User::factory()->create();
        $this->buyer->assignRole(role: Roles::BUYER);
    }

    public function test_admin_can_access_to_users_edit_screen(): void
    {
        $statuses = collect(UserStatus::asArray())->map(fn ($status) => [
            'key' => $status,
            'value' => trans($status),
        ])->toArray();

        $response = $this
            ->actingAs($this->admin)
            ->get(route(name: 'admin.users.edit', parameters: ['user' => $this->buyer->id]));

        $response->assertOk()
            ->assertViewIs(value: 'admin.users.crud.edit')
            ->assertViewHas('user', $this->buyer)
            ->assertViewHas('statuses', $statuses);
    }
}
