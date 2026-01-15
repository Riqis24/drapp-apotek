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
        Schema::create('pres_mstr', function (Blueprint $table) {
            $table->id('pres_mstr_id');

            $table->string('pres_mstr_code')->unique();        // RX-2026-0001
            $table->string('pres_mstr_name');                  // nama racikan
            $table->string('pres_mstr_doctor')->nullable();    // nama dokter

            $table->enum('pres_mstr_type', [
                'prescription',
                'compound'
            ])->default('prescription');

            $table->unsignedInteger('pres_mstr_qty');          // qty hasil racikan

            $table->enum('pres_mstr_status', [
                'draft',
                'ready',
                'paid',
                'cancel'
            ])->default('draft');

            $table->decimal('pres_mstr_mat', 15, 2)->default(0); // total bahan
            $table->decimal('pres_mstr_fee', 15, 2)->default(0); // jasa racik
            $table->decimal('pres_mstr_mark', 15, 2)->default(0); // markup
            $table->decimal('pres_mstr_total', 15, 2)->default(0); // total harga

            $table->foreignId('pres_mstr_smid')
                ->nullable();


            $table->foreignId('pres_mstr_createdby')
                ->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pres_mstr');
    }
};
