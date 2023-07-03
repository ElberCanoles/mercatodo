<?php

namespace Tests\Feature\Admin\Products;

use App\Contracts\Repository\Product\ProductWriteRepositoryInterface;
use App\Domain\Products\Enums\ProductStatus;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Enums\RoleType;
use App\Domain\Users\Models\User;
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
        $this->seed(class: RoleSeeder::class);
        $this->admin = User::factory()->create()->assignRole(RoleType::ADMINISTRATOR);
        $this->product = Product::factory()->create(['price' => 10, 'stock' => 10, 'status' => ProductStatus::AVAILABLE]);

        $imagePath = tempnam(sys_get_temp_dir(), prefix: 'images');
        $imageContent = file_get_contents(filename: 'https://via.placeholder.com/150');
        file_put_contents($imagePath, $imageContent);

        $this->image = new UploadedFile($imagePath, originalName: 'image.jpg', mimeType: null, error: null, test: true);
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

    public function test_admin_can_not_update_products_when_internal_error(): void
    {
        $this->mock(abstract: ProductWriteRepositoryInterface::class, mock: function ($mock) {
            $mock->shouldReceive('update')->andReturn(false);
        });

        $response = $this
            ->actingAs($this->admin)
            ->put(route(name: 'admin.products.update', parameters: ['product' => $this->product->id]), [
                'name' => 'New name',
                'description' => 'New description',
                'price' => 20,
                'stock' => 0,
                'status' => ProductStatus::UNAVAILABLE,
                'images' => [$this->image]
            ]);

        $response->assertStatus(status: 500);

        Mockery::close();
    }
}
