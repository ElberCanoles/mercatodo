<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Product;

use App\Domain\Products\Enums\ProductStatus;
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

class UpdateProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Product $product;
    private UploadedFile $image;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake(config(key: 'filesystems.default'));

        $this->seed(class: RoleSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole(role: Roles::ADMINISTRATOR);
        $this->admin->givePermissionTo(permission: Permission::create(['name' => Permissions::PRODUCTS_UPDATE]));
        $this->product = Product::factory()->create(['price' => 10, 'stock' => 10, 'status' => ProductStatus::AVAILABLE]);

        $this->image = UploadedFile::fake()->image(name: 'image.png')->size(kilobytes: 100);
    }

    public function test_admin_can_update_products(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->put(route(name: 'admin.products.update', parameters: ['product' => $this->product->id]), [
                'name' => 'New name',
                'description' => 'New description',
                'price' => 20.0,
                'stock' => 0,
                'status' => ProductStatus::UNAVAILABLE,
                'images' => [$this->image]
            ]);

        $response->assertOk()
            ->assertSessionDoesntHaveErrors();

        $this->product->refresh();

        $this->assertSame(expected: 'New name', actual: $this->product->name);
        $this->assertSame(expected: 'New description', actual: $this->product->description);
        $this->assertSame(expected: 20.0, actual: $this->product->price);
        $this->assertSame(expected: 0, actual: $this->product->stock);
        $this->assertSame(expected: ProductStatus::UNAVAILABLE, actual: $this->product->status);
    }
}
