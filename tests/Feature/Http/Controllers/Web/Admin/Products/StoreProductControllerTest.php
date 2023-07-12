<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Products;

use App\Domain\Products\Models\Product;
use App\Domain\Users\Enums\Permissions;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\Permission;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class StoreProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private UploadedFile $image;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole(role: Roles::ADMINISTRATOR);
        $this->admin->givePermissionTo(permission: Permission::create(['name' => Permissions::PRODUCTS_CREATE]));

        $imagePath = tempnam(directory: sys_get_temp_dir(), prefix: 'images');
        $imageContent = file_get_contents(filename: 'https://via.placeholder.com/150');
        file_put_contents($imagePath, $imageContent);

        $this->image = new UploadedFile($imagePath, originalName: 'image.jpg', mimeType: null, error: null, test: true);
    }

    public function test_admin_can_store_products(): void
    {
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
