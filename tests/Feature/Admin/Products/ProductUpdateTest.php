<?php

namespace Tests\Feature\Admin\Products;

use App\Contracts\Repository\Product\ProductWriteRepositoryInterface;
use App\Enums\Product\ProductStatus;
use App\Enums\User\RoleType;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;

class ProductUpdateTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Product $product;
    private UploadedFile $image;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->admin = User::factory()->create()->assignRole(RoleType::ADMINISTRATOR);
        $this->product = Product::factory()->create(['price' => 10, 'stock' => 10, 'status' => ProductStatus::AVAILABLE]);

        $imagePath = tempnam(sys_get_temp_dir(), 'images');
        $imageContent = file_get_contents('https://via.placeholder.com/150');
        file_put_contents($imagePath, $imageContent);

        $this->image = new UploadedFile($imagePath, 'image.jpg', null, null, true);
    }

    public function test_admin_can_update_products(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->put(route('admin.products.update', ['product' => $this->product->id]), [
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

        $this->assertSame('New name', $this->product->name);
        $this->assertSame('New description', $this->product->description);
        $this->assertSame(20.0, $this->product->price);
        $this->assertSame(0, $this->product->stock);
        $this->assertSame(ProductStatus::UNAVAILABLE, $this->product->status);
    }

    public function test_admin_can_not_update_products_when_internal_error(): void
    {
        $this->mock(ProductWriteRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('update')->andReturn(null);
        });

        $response = $this
            ->actingAs($this->admin)
            ->put(route('admin.products.update', ['product' => $this->product->id]), [
                'name' => 'New name',
                'description' => 'New description',
                'price' => 20,
                'stock' => 0,
                'status' => ProductStatus::UNAVAILABLE,
                'images' => [$this->image]
            ]);

        $response->assertStatus(500);

        Mockery::close();
    }
}
