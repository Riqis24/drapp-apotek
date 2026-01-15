<?php

namespace Database\Factories;

use App\Models\Measurement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Measurement>
 */
class MeasurementFactory extends Factory
{
    protected $model = Measurement::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['pcs', 'kg', 'liter', 'meter', 'kaleng']),
        ];
    }
}
