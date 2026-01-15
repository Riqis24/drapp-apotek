<?php

namespace Database\Seeders;

use App\Models\ProductMeasurements;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductMeasurementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductMeasurements::create([
            'product_id' => 1,
            'measurement_id' => 1,
            'placement_id' => 1,
            'conversion' => 1,
        ]);

        ProductMeasurements::create([
            'product_id' => 1,
            'measurement_id' => 2,
            'placement_id' => 1,
            'conversion' => 4,
        ]);

        ProductMeasurements::create([
            'product_id' => 1,
            'measurement_id' => 3,
            'placement_id' => 1,
            'conversion' => 40,
        ]);

        ProductMeasurements::create([
            'product_id' => 2,
            'measurement_id' => 1,
            'placement_id' => 2,
            'conversion' => 1,
        ]);

        ProductMeasurements::create([
            'product_id' => 2,
            'measurement_id' => 2,
            'placement_id' => 2,
            'conversion' => 5,
        ]);

        ProductMeasurements::create([
            'product_id' => 2,
            'measurement_id' => 3,
            'placement_id' => 2,
            'conversion' => 50,
        ]);

        ProductMeasurements::create([
            'product_id' => 3,
            'measurement_id' => 1,
            'placement_id' => 3,
            'conversion' => 1,
        ]);

        ProductMeasurements::create([
            'product_id' => 3,
            'measurement_id' => 2,
            'placement_id' => 3,
            'conversion' => 6,
        ]);

        ProductMeasurements::create([
            'product_id' => 3,
            'measurement_id' => 3,
            'placement_id' => 3,
            'conversion' => 60,
        ]);
    }
}
