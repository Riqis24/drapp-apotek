<?php

namespace Database\Seeders;

use App\Models\ProductPlacement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductPlacementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductPlacement::create([
            'code' => 'PP-001',
            'name' => 'RAK A',
            'description' => 'RAK A BAWAH',
        ]);
        ProductPlacement::create([
            'code' => 'PP-002',
            'name' => 'RAK A',
            'description' => 'RAK A ATAS',
        ]);
        ProductPlacement::create([
            'code' => 'PP-003',
            'name' => 'RAK B',
            'description' => 'RAK B ATAS',
        ]);
        ProductPlacement::create([
            'code' => 'PP-004',
            'name' => 'RAK B',
            'description' => 'RAK B BAWAH',
        ]);
    }
}
