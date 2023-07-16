<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\User;

use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexUserControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
        $this->user = User::factory()->create();
        $this->user->assignRole(role: Roles::ADMINISTRATOR);
    }


    public function test_admin_can_access_to_users_list_screen(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route(name: 'admin.users.index'));

        $response->assertOk()
            ->assertViewIs(value: 'admin.users.index');
    }


    public function test_admin_can_get_users_list_paginated_data(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->getJson(route(name: 'admin.users.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [],
                'links' => [],
                'meta' => [
                    "current_page",
                    "from",
                    "last_page",
                    "links" => [
                    ],
                    "path",
                    "per_page",
                    "to",
                    "total"
                ]
            ]);
    }
}
