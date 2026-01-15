<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SuppMstrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('supp_mstr')->insert([
            [
                'supp_mstr_code' => 'VD-001',
                'supp_mstr_name' => 'VENDOR A',
                'supp_mstr_addr' => 'ALAMAT VD A',
                'supp_mstr_phone' => '02154879865',
                'supp_mstr_npwp' => '123546897654321',
                'supp_mstr_ppn' => '1',
                'supp_mstr_active' => '1',
            ],
            [
                'supp_mstr_code' => 'VD-002',
                'supp_mstr_name' => 'VENDOR B',
                'supp_mstr_addr' => 'ALAMAT VD B',
                'supp_mstr_phone' => '02154879865',
                'supp_mstr_npwp' => '123546897654321',
                'supp_mstr_ppn' => '1',
                'supp_mstr_active' => '1',
            ],
            [
                'supp_mstr_code' => 'VD-003',
                'supp_mstr_name' => 'VENDOR C',
                'supp_mstr_addr' => 'ALAMAT VD C',
                'supp_mstr_phone' => '02154879865',
                'supp_mstr_npwp' => '123546897654321',
                'supp_mstr_ppn' => '1',
                'supp_mstr_active' => '1',
            ],
        ]);
    }
}
