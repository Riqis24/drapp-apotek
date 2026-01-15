<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supp_mstr', function (Blueprint $table) {
            $table->id('supp_mstr_id');
            $table->string('supp_mstr_code', 30)->unique();
            $table->string('supp_mstr_name', 150);
            $table->text('supp_mstr_addr')->nullable();
            $table->string('supp_mstr_phone', 30)->nullable();
            $table->string('supp_mstr_npwp', 30)->nullable();
            $table->boolean('supp_mstr_ppn')->default(0); // 1 = PKP
            $table->boolean('supp_mstr_active')->default(1);
            $table->timestamps();

            $table->index('supp_mstr_name');
            $table->index('supp_mstr_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supp_mstr');
    }
};
