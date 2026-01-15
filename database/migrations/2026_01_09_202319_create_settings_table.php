<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Contoh: 'allow_negative_stock'
            $table->text('value')->nullable(); // Contoh: '1' atau '0'
            $table->string('group')->default('general'); // Untuk kategori setting
            $table->timestamps();
        });

        // Masukkan data default langsung saat migrasi
        DB::table('settings')->insert([
            [
                'key'   => 'allow_negative_stock',
                'value' => '0', // Default: Tidak boleh minus
                'group' => 'inventory',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key'   => 'app_name',
                'value' => 'Drapp Apotek',
                'group' => 'general',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
