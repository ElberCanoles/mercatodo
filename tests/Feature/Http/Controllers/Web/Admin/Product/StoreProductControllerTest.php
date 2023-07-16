<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Product;

use App\Domain\Products\Models\Product;
use App\Domain\Users\Enums\Permissions;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\Permission;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private UploadedFile $image;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake(config(key: 'filesystems.default'));

        $this->seed(class: RoleSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole(role: Roles::ADMINISTRATOR);
        $this->admin->givePermissionTo(permission: Permission::create(['name' => Permissions::PRODUCTS_CREATE]));

        $this->image = UploadedFile::fake()->image(name: 'image.png')->size(kilobytes: 100);
    }

    public function test_admin_can_store_products(): void
    {
        /**
         * @var Product $product
         */
        $product = Product::factory()->make();

        $data = [
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'status' => $product->status,
            'images' => [$this->image]
        ];

        $response = $this->actingAs($this->admin)
            ->post(route(name: 'admin.products.store'), $data);

        $response->assertSessionDoesntHaveErrors()
            ->assertOk();

        $this->assertDatabaseCount(table: 'products', count: 1);
    }
}
