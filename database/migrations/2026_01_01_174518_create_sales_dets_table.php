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
        Schema::create('sales_det', function (Blueprint $table) {
            $table->bigIncrements('sales_det_id');

            $table->unsignedBigInteger('sales_det_mstrid');
            $table->unsignedBigInteger('sales_det_productid');

            // UM & konversi
            $table->unsignedBigInteger('sales_det_um')->nullable();
            $table->decimal('sales_det_umconv', 15, 4)->default(1);

            // qty input & qty real (after conv)
            $table->decimal('sales_det_qty', 15, 2);
            $table->decimal('sales_det_qtyconv', 15, 2);
            $table->decimal('sales_det_qtyreturn', 15, 2)->default(0);

            // harga
            $table->decimal('sales_det_price', 15, 2)->default(0);
            $table->decimal('sales_det_priceconv', 15, 2)->default(0);

            // diskon
            $table->enum('sales_det_disctype', ['percent', 'amount'])->nullable();
            $table->decimal('sales_det_discvalue', 15, 2)->default(0);
            $table->decimal('sales_det_discamt', 15, 2)->default(0);

            // total
            $table->decimal('sales_det_subtotal', 15, 2)->default(0);

            // batch & lokasi (penting untuk FIFO)
            $table->unsignedBigInteger('sales_det_locid');
            $table->unsignedBigInteger('sales_det_batchid')->nullable();

            // type bundle/single/racikan
            $table->string('sales_det_type')->nullable();
            $table->unsignedBigInteger('sales_det_parentid')->nullable();

            // resep
            $table->boolean('sales_det_comp')->default(false);
            $table->unsignedBigInteger('sales_det_pmid')->nullable();

            // custom timestamp
            $table->timestamp('sales_det_createdat')->nullable();
            $table->timestamp('sales_det_updatedat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_dets');
    }
};
