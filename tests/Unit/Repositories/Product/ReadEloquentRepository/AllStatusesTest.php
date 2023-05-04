<?php

namespace Tests\Unit\Repositories\Product\ReadEloquentRepository;

use App\Enums\Product\ProductStatus;
use App\Models\Product;
use App\Repositories\Product\ProductReadEloquentRepository;
use Tests\TestCase;

class AllStatusesTest extends TestCase
{
    public function test_all_statuses(): void
    {
        $repository = new ProductReadEloquentRepository(new Product());

        $result = $repository->allStatuses();

        $this->assertIsArray($result);
        $this->assertSameSize(ProductStatus::asArray(), $result);
    }
}
