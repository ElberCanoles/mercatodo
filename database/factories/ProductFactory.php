<?php

namespace Database\Factories;

use App\Domain\Products\Enums\ProductStatus;
use App\Domain\Products\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = Str::random();

        $statuses = array_values(ProductStatus::asArray());

        return [
            'name' => Str::ucfirst(Str::lower($name)),
            'slug' => Str::slug(title: Str::random(length: 6).' '.$name.' '.Str::random(length: 4)),
            'description' => Str::ucfirst(Str::lower(fake()->sentence())),
            'price' => rand(1000, 500000),
            'stock' => rand(10, 100),
            'status' => $statuses[rand(0, count($statuses) - 1)],
        ];
    }
}
