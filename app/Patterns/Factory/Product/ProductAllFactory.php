<?php

declare(strict_types=1);

namespace App\Patterns\Factory\Product;

use App\Enums\General\SystemParams;
use App\Enums\User\RoleType;
use App\Models\Product;
use App\Traits\Utilities\CheckAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

final class ProductAllFactory
{
    use CheckAttribute;

    private Product $product;
    private array $queryParams;
    private array $arguments;
    private int $lengthPerPage;


    public function __construct(Product $product, array $queryParams = [], ...$arguments)
    {
        $this->product = $product;
        $this->queryParams = $queryParams;
        $this->arguments = $arguments;
        $this->lengthPerPage = SystemParams::LENGTH_PER_PAGE;
    }

    public function make(): LengthAwarePaginator
    {
        return match ($this->arguments['roleTarget'] ?? null) {
            RoleType::ADMINISTRATOR => $this->buildQueryForAdmin(),
            RoleType::BUYER => $this->buildQueryForBuyer(),
            default => $this->product::query()->paginate($this->lengthPerPage)
        };
    }

    private function buildCommonQuery(Builder &$query): void
    {
        if ($this->isDefined(attribute: $this->queryParams['name'] ?? null)) {
            $query = $query->where(column: 'name', operator: 'like', value: '%' . $this->queryParams['name'] . '%');
        }

        if ($this->isDefined(attribute: $this->queryParams['per_page'] ?? null) && $this->queryParams['per_page'] <= $this->lengthPerPage) {
            $this->lengthPerPage = intval($this->queryParams['per_page']);
        }
    }

    private function buildQueryForAdmin(): LengthAwarePaginator
    {
        $query = $this->product::query()
            ->select(columns: ['id', 'name', 'price', 'stock', 'status', 'created_at']);

        $this->buildCommonQuery($query);

        if ($this->isDefined(attribute: $this->queryParams['price'] ?? null)) {
            $query = $query->where(column: 'price', operator: 'like', value: '%' . $this->queryParams['price'] . '%');
        }

        if ($this->isDefined(attribute: $this->queryParams['stock'] ?? null)) {
            $query = $query->where(column: 'stock', operator: 'like', value: '%' . $this->queryParams['stock'] . '%');
        }

        if ($this->isDefined(attribute: $this->queryParams['status'] ?? null)) {
            $query = $query->where(column: 'status', operator: '=', value: $this->queryParams['status']);
        }

        return $query->orderBy(column: 'created_at', direction: 'DESC')
            ->orderBy(column: 'id', direction: 'DESC')
            ->paginate(perPage: $this->lengthPerPage)->through(fn ($product) => [
                'name' => $product->name,
                'price' => number_format(num: $product->price, decimal_separator: ',', thousands_separator: '.'),
                'stock' => number_format(num: $product->stock, decimal_separator: ',', thousands_separator: '.'),
                'status_key' => $product->status,
                'status_value' => trans($product->status),
                'created_at' => $product->created_at->format('d-m-Y'),
                'edit_url' => route(name: 'admin.products.edit', parameters: ['product' => $product->id]),
                'delete_url' => route(name: 'admin.products.destroy', parameters: ['product' => $product->id]),
            ]);
    }

    private function buildQueryForBuyer(): LengthAwarePaginator
    {
        $query = $this->product::query()
            ->select(columns: ['id', 'name', 'slug', 'price', 'stock']);

        $this->buildCommonQuery($query);

        if ($this->isDefined(attribute: $this->arguments['status'] ?? null)) {
            $query = $query->where(column: 'status', operator: '=', value: $this->arguments['status']);
        }

        if ($this->isDefined(attribute: $this->queryParams['minimum_price'] ?? null)) {
            $query = $query->where(column: 'price', operator: '>=', value: $this->queryParams['minimum_price']);
        }

        if ($this->isDefined(attribute: $this->queryParams['maximum_price'] ?? null)) {
            $query = $query->where(column: 'price', operator: '<=', value: $this->queryParams['maximum_price']);
        }

        return $query->orderBy(column: 'products.created_at', direction: 'DESC')
            ->orderBy(column: 'products.id', direction: 'DESC')
            ->paginate(perPage: $this->lengthPerPage)->through(fn ($product) => [
                'name' => $product->name,
                'images' => $product->images,
                'price' => number_format(num: $product->price, decimal_separator: ',', thousands_separator: '.'),
                'stock' => number_format(num: $product->stock, decimal_separator: ',', thousands_separator: '.'),
                'show_url' => route(name: 'buyer.products.show', parameters: ['slug' => $product->slug]),
            ]);
    }
}
