<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->word(),
            'name' => $this->faker->word(),
            'description' => $this->faker->word(),
            'measurement_id' => \App\Models\Measurement::inRandomOrder()->first()?->id
                ?? \App\Models\Measurement::factory(), // fallback kalau belum ada measurement
        ];
    }
}
