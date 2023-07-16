<?php

namespace Database\Factories;

use App\Domain\Exports\Enums\ExportModules;
use App\Domain\Exports\Models\Export;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Export>
 */
class ExportFactory extends Factory
{
    protected $model = Export::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $modules = array_values(ExportModules::asArray());

        return [
            'module' => $modules[rand(0, count($modules) - 1)],
            'path' => fake()->url()
        ];
    }
}
