<?php

namespace Database\Seeders;

use App\Models\Price;
use App\Models\Measurement;
use App\Models\Product;
use App\Models\ProductMeasurements;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // 2. Tambah Produk
        $product1 = Product::create([
            'code' => 'OBAT-001',
            'name' => 'Panadol',
            'description' => 'Panadol',
            'category' => 1, // Default-nya adalah Tablet
            'measurement_id' => 1, // Default-nya adalah Tablet
            'margin' => 10, // Default-nya adalah Tablet
            'type' => 'single',
            'is_stockable' => true,
            'is_visible' => true,
        ]);

        $product2 = Product::create([
            'code' => 'OBAT-002',
            'name' => 'Paramex',
            'description' => 'Paramex',
            'category' => 1,
            'measurement_id' => 1, // Default-nya adalah Tablet
            'margin' => 10, // Default-nya adalah Tablet
            'type' => 'single',
            'is_stockable' => true,
            'is_visible' => true,
        ]);

        $product3 = Product::create([
            'code' => 'OBAT-003',
            'name' => 'Flumin',
            'description' => 'Flumin',
            'category' => 1,
            'measurement_id' => 1, // Default-nya adalah Tablet
            'margin' => 10, // Default-nya adalah Tablet
            'type' => 'single',
            'is_stockable' => true,
            'is_visible' => true,
        ]);
    }

    // // Fungsi untuk menambahkan harga pada product_measurement
    // private function createPrice($productMeasurement, $price)
    // {
    //     Price::create([
    //         'product_measurement_id' => $productMeasurement->id,
    //         'price' => $price,
    //     ]);
    // }
}
