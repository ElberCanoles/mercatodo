<?php

namespace Tests\Feature\Admin\Products;

use App\Contracts\Repository\Product\ProductWriteRepositoryInterface;
use App\Domain\Products\Models\Product;
use App\Enums\User\RoleType;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;

class ProductStoreTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private UploadedFile $image;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
        $this->admin = User::factory()->create()->assignRole(RoleType::ADMINISTRATOR);

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

    public function test_admin_can_not_store_products_when_internal_error(): void
    {
        $product = Product::factory()->make();

        $this->mock(abstract: ProductWriteRepositoryInterface::class, mock: function ($mock) {
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
            ->post(route(name: 'admin.products.store'), $data);

        $response->assertStatus(status: 500);

        Mockery::close();
    }
}
