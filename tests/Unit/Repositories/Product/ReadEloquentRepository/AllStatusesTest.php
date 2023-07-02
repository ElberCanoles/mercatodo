<?php

namespace Tests\Unit\Repositories\Product\ReadEloquentRepository;

use App\Domain\Products\Enums\ProductStatus;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Repositories\ProductReadEloquentRepository;
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
