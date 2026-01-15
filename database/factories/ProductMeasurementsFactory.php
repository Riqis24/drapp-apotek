<?php

namespace Database\Factories;

use App\Models\ProductMeasurements;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductMeasurements>
 */
class ProductMeasurementsFactory extends Factory
{
    protected $model = ProductMeasurements::class;

    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'measurement_id' => \App\Models\Measurement::factory(),
            'conversion' => $this->faker->randomFloat(2, 0.1, 5),
        ];
    }
}
