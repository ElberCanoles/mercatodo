<?php

namespace Tests\Unit\Repositories\Product\ReadEloquentRepository;

use App\Domain\Products\Models\Product;
use App\Domain\Products\Repositories\ProductReadEloquentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class AllTest extends TestCase
{
    use RefreshDatabase;

    public function test_all(): void
    {
        Product::factory()->count(count: 5)->create();
        $repository = new ProductReadEloquentRepository(new Product());

        $result = $repository->all();

        $this->assertInstanceOf(expected: LengthAwarePaginator::class, actual: $result);
        $this->assertEquals(expected: 5, actual: $result->total());
    }
}
