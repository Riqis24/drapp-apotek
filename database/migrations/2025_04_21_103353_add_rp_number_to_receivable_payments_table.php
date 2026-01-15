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
        Schema::table('receivable_payments', function (Blueprint $table) {
            $table->string('rp_number')->unique()->nullable(); // Menambahkan kolom rp_number setelah kolom note
        });
    }

    public function down()
    {
        Schema::table('receivable_payments', function (Blueprint $table) {
            $table->dropColumn('rp_number'); // Menghapus kolom rp_number
        });
    }
};
