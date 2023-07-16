<?php

namespace Database\Factories;

use App\Domain\Imports\Enums\ImportModules;
use App\Domain\Imports\Models\Import;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Import>
 */
class ImportFactory extends Factory
{
    protected $model = Import::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $modules = array_values(ImportModules::asArray());

        return [
            'module' => $modules[rand(0, count($modules) - 1)],
            'path' => fake()->url(),
            'summary' => ["failed_records" => rand(0, 10), "created_records" => rand(0, 10), "updated_records" => rand(0, 10)],
            'errors' => null,
        ];
    }
}
