<?php

declare(strict_types=1);

namespace App\Domain\Products\UserQueryBuilder;

use App\Domain\Products\Models\Product;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Product|null find($id, $columns = ['*'])
 * @method static Product|null first()
 * @method static Product create(array $attributes = [])
 */
class ProductQueryBuilder extends Builder
{
    public function whereColumContains(string $column, ?string $value): self
    {
        if ($value == null) return $this;

        return $this->where(column: $column, operator: 'like', value: '%' . $value . '%');
    }

    public function whereStatus(?string $status): self
    {
        if ($status == null) return $this;

        return $this->where(column: 'status', operator: '=', value: $status);
    }

    public function wherePriceGreaterThanOrEqualsTo(float|string|null $value): self
    {
        if ($value == null) return $this;

        return $this->where(column: 'price', operator: '>=', value: $value);
    }

    public function wherePriceLessThanOrEqualsTo(float|string|null $value): self
    {
        if ($value == null) return $this;

        return $this->where(column: 'price', operator: '<=', value: $value);
    }

}
