<?php

namespace Tests\Feature\Admin\Products;

use App\Enums\User\RoleType;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Product\ProductRepositoryInterface;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Mockery;

class ProductStoreTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private UploadedFile $image;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->admin = User::factory()->create()->assignRole(RoleType::ADMINISTRATOR);

        $imagePath = tempnam(sys_get_temp_dir(), 'images');
        $imageContent = file_get_contents('https://via.placeholder.com/150');
        file_put_contents($imagePath, $imageContent);

        $this->image = new UploadedFile($imagePath, 'image.jpg', null, null, true);
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
            ->post(route('admin.products.store'), $data);

        $response->assertSessionDoesntHaveErrors()
            ->assertOk();

        $this->assertDatabaseCount('products', 1);
    }

    public function test_admin_can_not_store_products_when_internal_error(): void
    {
        $product = Product::factory()->make();

        $this->mock(ProductRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('store')->andReturn(null);
        });

        $data = [
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'status' => $product->status,
            'images' => [$this->image]
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), $data);

        $response->assertStatus(500);

        Mockery::close();
    }
}
