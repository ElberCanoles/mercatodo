<?php

declare(strict_types=1);

namespace App\Domain\Products\Presenters;

use App\Domain\Products\Models\Product;

class ProductPresenter
{
    private static ?ProductPresenter $instance = null;

    private Product $product;

    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    public function price(): string
    {
        return number_format(num: $this->product->price, decimal_separator: ',', thousands_separator: '.');
    }

    public function stock(): string
    {
        return number_format(num: $this->product->stock, decimal_separator: ',', thousands_separator: '.');
    }

    public function statusTranslated(): string
    {
        return trans($this->product->status);
    }

    public function createdAt(): string
    {
        return $this->product->created_at->format(format: 'd-m-Y');
    }

    public function adminEditUrl(): string
    {
        return route(name: 'admin.products.edit', parameters: ['product' => $this->product->id]);
    }

    public function adminDestroyUrl(): string
    {
        return route(name: 'admin.products.destroy', parameters: ['product' => $this->product->id]);
    }

    public function buyerAddToCartUrl(): string
    {
        return route(name: 'buyer.products.add.to.cart', parameters: ['product' => $this->product->id]);
    }

    public function guestShowUrl(): string
    {
        return route(name: 'products.show', parameters: ['slug' => $this->product->slug]);
    }

    public function apiShowUrl(): string
    {
        return route(name: 'api.products.show', parameters: ['product' => $this->product->id]);
    }

    public function apiUpdateUrl(): string
    {
        return route(name: 'api.products.update', parameters: ['product' => $this->product->id]);
    }

    public function apiDestroyUrl(): string
    {
        return route(name: 'api.products.destroy', parameters: ['product' => $this->product->id]);
    }

}
