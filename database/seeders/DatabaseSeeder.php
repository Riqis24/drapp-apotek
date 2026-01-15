<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            MeasurementSeeder::class,
            ProductSeeder::class,
            SuppMstrSeeder::class,
            LocMstrSeeder::class,
            ProductPlacementSeeder::class,
            ProductMeasurementsSeeder::class,
            ProductCatSeeder::class,
        ]);
        User::create([
            'user_mstr_name' => 'Riqi Saputra',
            'user_mstr_email' => 'riqi@mail.com',
            'email_verified_at' => now(),
            'user_mstr_password' => 'password',
            'remember_token' => Str::random(10),
        ]);

        // \App\Models\Product::factory(100)->create()->each(function ($product) {
        //     // Assign 2 satuan random ke setiap produk
        //     $measurements = \App\Models\Measurement::inRandomOrder()->take(2)->get();

        //     foreach ($measurements as $measurement) {
        //         $pm = \App\Models\ProductMeasurements::create([
        //             'product_id' => $product->id,
        //             'measurement_id' => $measurement->id,
        //             'conversion' => fake()->randomFloat(2, 1, 10)
        //         ]);

        //         \App\Models\Price::create([
        //             'product_measurement_id' => $pm->id,
        //             'price' => fake()->randomFloat(2, 5000, 100000),
        //         ]);
        //     }
        // });
    }
}
