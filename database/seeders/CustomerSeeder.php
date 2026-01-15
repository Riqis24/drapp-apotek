<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'name' => 'Customer Umum',
            'phone' => null,
            'address' => null,
        ]);

        Customer::factory()->count(100)->create(); // generate 10 data customer


    }
}
