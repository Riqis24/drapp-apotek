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
        Schema::create('sr_det', function (Blueprint $table) {
            $table->id('sr_det_id');

            $table->unsignedBigInteger('sr_det_mstrid');
            $table->unsignedBigInteger('sr_det_sdid');

            $table->unsignedBigInteger('sr_det_productid');
            $table->unsignedBigInteger('sr_det_um');
            $table->decimal('sr_det_umconv', 15, 4);

            $table->decimal('sr_det_qty', 15, 4);
            $table->decimal('sr_det_qtyconv', 15, 4);

            $table->unsignedBigInteger('sr_det_batchid');

            $table->decimal('sr_det_price', 15, 2)->default(0);
            $table->decimal('sr_det_subtotal', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sr_det');
    }
};
