<?php

namespace Database\Factories;

use App\Enums\Product\ProductStatus;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
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
            'name' => $name,
            'slug' => Str::slug(Str::random(6).' '.$name.' '.Str::random(4)),
            'description' => fake()->sentence(),
            'price' => rand(1000, 500000),
            'stock' => rand(10, 100),
            'status' => $statuses[rand(0, count($statuses) - 1)],
        ];
    }
}
