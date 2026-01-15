<?php

namespace Database\Factories;

use App\Models\Price;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Price>
 */
class PriceFactory extends Factory
{
    protected $model = Price::class;

    public function definition(): array
    {
        return [
            'product_measurement_id' => \App\Models\ProductMeasurements::factory(),
            'price' => $this->faker->randomFloat(2, 1000, 1000000),
        ];
    }
}
