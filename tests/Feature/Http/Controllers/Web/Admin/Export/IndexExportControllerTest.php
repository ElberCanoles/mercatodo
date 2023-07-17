<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Export;

use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexExportControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
        $this->user = User::factory()->create();
    }

    public function test_guest_user_is_redirected_to_login(): void
    {
        $this->get(route(name: 'admin.exports.index'))
            ->assertRedirect(uri: '/login');
    }

    public function test_authorized_user_can_access_to_exports_screen(): void
    {
        $this->user->assignRole(role: Roles::ADMINISTRATOR);

        $this->actingAs($this->user)
            ->get(route(name: 'admin.exports.index'))
            ->assertOk()
            ->assertViewIs(value: 'admin.exports.index');
    }

    public function test_authorized_user_can_get_exports_list_data(): void
    {
        $this->user->assignRole(role: Roles::ADMINISTRATOR);

        $this->actingAs($this->user)
            ->getJson(route(name: 'admin.exports.index'))
            ->assertOk()
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

    public function test_unauthorized_user_can_not_access_to_exports_screen(): void
    {
        $this->actingAs($this->user)
            ->get(route(name: 'admin.exports.index'))
            ->assertForbidden();
    }

    public function test_unauthorized_user_can_not_get_exports_list_data(): void
    {
        $this->actingAs($this->user)
            ->getJson(route(name: 'admin.exports.index'))
            ->assertForbidden();
    }
}
