<?php

namespace Database\Seeders;

use App\Domain\Products\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()->count(count: 1000)->create()->each(function ($product) {
            $product->images()->create([
                'path' => "https://ui-avatars.com/api/?name={$product->name}&background=0D8ABC&color=fff&size=128"
            ]);
        });
    }
}
