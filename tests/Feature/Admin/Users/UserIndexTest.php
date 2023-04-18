<?php

namespace Tests\Feature\Admin\Users;

use App\Enums\User\RoleType;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserIndexTest extends TestCase
{

    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->user = User::factory()->create()->assignRole(RoleType::ADMINISTRATOR);
    }


    public function test_admin_can_access_to_users_list_screen(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('admin.users.index'));

        $response->assertOk()
            ->assertViewIs('admin.users.index');
    }


    public function test_admin_can_get_users_list_paginated_data(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->getJson(route('admin.users.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'current_page',
                'data' => [],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links' => [],
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total'
            ]);
    }
}
