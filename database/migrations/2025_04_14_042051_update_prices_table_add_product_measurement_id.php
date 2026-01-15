<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('prices', function (Blueprint $table) {
            // Menambahkan kolom 'product_measurement_id'
            $table->foreignId('product_measurement_id')->constrained('product_measurements')->onDelete('cascade');

            // Menghapus kolom 'product_id' dan 'measurement_id'
            $table->dropForeign(['product_id']);
            $table->dropForeign(['measurement_id']);
            $table->dropColumn(['product_id', 'measurement_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('prices', function (Blueprint $table) {
            // Menambahkan kembali kolom 'product_id' dan 'measurement_id'
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('measurement_id')->constrained()->onDelete('cascade');

            // Menghapus kolom 'product_measurement_id'
            $table->dropForeign(['product_measurement_id']);
            $table->dropColumn('product_measurement_id');
        });
    }
};
