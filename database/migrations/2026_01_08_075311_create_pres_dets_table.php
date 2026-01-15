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
        Schema::create('pres_det', function (Blueprint $table) {
            $table->id('pres_det_id');

            $table->unsignedBigInteger('pres_det_mstrid')->nullable();

            $table->unsignedBigInteger('pres_det_productid')->nullable();

            $table->unsignedBigInteger('pres_det_um')->nullable();

            $table->unsignedBigInteger('pres_det_batchid')->nullable();

            $table->decimal('pres_det_qty', 15, 4);   // qty bahan dipakai (TOTAL)
            $table->decimal('pres_det_price', 15, 2); // subtotal bahan

            $table->timestamps();

            // $table->unique([
            //     'pres_mstr_id',
            //     'product_id',
            //     'batch_id'
            // ], 'uniq_pres_det');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pres_dets');
    }
};
