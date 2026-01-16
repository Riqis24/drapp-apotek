<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocMstrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('loc_mstr')->insert([
            [
                'loc_mstr_code' => 'WH-001',
                'loc_mstr_name' => 'Apotek',
                'loc_mstr_active' => '1',
                'loc_mstr_isvisible' => '1',
            ],
            [
                'loc_mstr_code' => 'WH-002',
                'loc_mstr_name' => 'Gudang Owner',
                'loc_mstr_active' => '1',
                'loc_mstr_isvisible' => '0',
            ],

        ]);
    }
}
