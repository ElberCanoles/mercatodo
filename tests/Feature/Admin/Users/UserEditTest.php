<?php

namespace Tests\Feature\Admin\Users;

use App\Contracts\Repository\User\UserReadRepositoryInterface;
use App\Enums\User\RoleType;
use App\Models\User;
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
        $this->admin = User::factory()->create()->assignRole(RoleType::ADMINISTRATOR);
        $this->buyer = User::factory()->create()->assignRole(RoleType::BUYER);
    }

    public function test_admin_can_access_to_users_edit_screen(): void
    {
        $readRepository = resolve(name: UserReadRepositoryInterface::class);

        $response = $this
            ->actingAs($this->admin)
            ->get(route(name: 'admin.users.edit', parameters: ['user' => $this->buyer->id]));

        $response->assertOk()
            ->assertViewIs(value: 'admin.users.crud.edit')
            ->assertViewHas('user', $this->buyer)
            ->assertViewHas('statuses', $readRepository->allStatuses());
    }
}
