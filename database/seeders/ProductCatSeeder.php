<?php

namespace Database\Seeders;

use App\Models\ProductCat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductCat::create([
            'product_cat_name' => 'Obat Umum',
            'product_cat_desc' => 'Semua kalangan',
        ]);
        ProductCat::create([
            'product_cat_name' => 'Obat Keras',
            'product_cat_desc' => 'Keras',
        ]);
        ProductCat::create([
            'product_cat_name' => 'Obat Tidur',
            'product_cat_desc' => 'Tidur',
        ]);
    }
}
