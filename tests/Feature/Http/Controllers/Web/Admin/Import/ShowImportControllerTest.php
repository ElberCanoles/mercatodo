<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Import;

use App\Domain\Imports\Models\Import;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowImportControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Import $import;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
        $this->user = User::factory()->create();
        $this->import = Import::factory()->create();
    }

    public function test_guest_user_is_redirected_to_login(): void
    {
        $this->get(route(name: 'admin.imports.show', parameters: ['import' => $this->import->id]))
            ->assertRedirect(uri: '/login');
    }

    public function test_authorized_user_can_access_to_imports_show_screen(): void
    {
        $this->user->assignRole(role: Roles::ADMINISTRATOR);

        $this->actingAs($this->user)
            ->get(route(name: 'admin.imports.show', parameters: ['import' => $this->import->id]))
            ->assertOk()
            ->assertViewIs(value: 'admin.imports.show')
            ->assertViewHas(key: 'import', value: $this->import);
    }

    public function test_unauthorized_user_can_not_access_to_imports_show_screen(): void
    {
        $this->actingAs($this->user)
            ->get(route(name: 'admin.imports.show', parameters: ['import' => $this->import->id]))
            ->assertForbidden();
    }
}
