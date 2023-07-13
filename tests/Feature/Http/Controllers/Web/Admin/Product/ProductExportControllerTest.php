<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Product;

use App\Domain\Products\Jobs\ProductExportJob;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductExportControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake(config(key: 'filesystems.default'));
        $this->seed(class: RoleSeeder::class);
        $this->user = User::factory()->create();
    }

    public function test_guest_user_is_redirect_to_login(): void
    {
        $this->get(route(name: 'admin.products.export'))
            ->assertRedirect(uri: '/login');
    }

    public function test_authorized_user_can_call_products_export_route(): void
    {
        $this->user->assignRole(role: Roles::ADMINISTRATOR);

        $this->actingAs($this->user)
            ->get(route(name: 'admin.products.export'))
            ->assertOk();
    }

    public function test_it_should_enqueue_product_export_job(): void
    {
        Queue::fake();

        $this->user->assignRole(role: Roles::ADMINISTRATOR);

        $this->actingAs($this->user)
            ->get(route(name: 'admin.products.export'))
            ->assertOk();

        Queue::assertPushed(job: ProductExportJob::class);
    }

    public function test_unauthorized_user_can_not_call_products_export_route(): void
    {
        $this->actingAs($this->user)
            ->get(route(name: 'admin.products.export'))
            ->assertForbidden();
    }

}
