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
        Schema::create('po_det', function (Blueprint $table) {
            $table->bigIncrements('po_det_id');

            $table->unsignedBigInteger('po_det_mstrid');
            $table->unsignedBigInteger('po_det_productid');
            $table->unsignedBigInteger('po_det_um')->nullable();
            $table->decimal('po_det_umconv', 15, 2)->nullable();

            $table->decimal('po_det_qty', 15, 2);
            $table->decimal('po_det_qtyrcvd', 15, 2)->nullable();
            $table->decimal('po_det_qtyremain', 15, 2)->nullable();
            $table->decimal('po_det_qtyreturn', 15, 2)->nullable();
            $table->decimal('po_det_price', 15, 2)->default(0);

            $table->string('po_det_disctype', 10)->nullable(); // percent | amount
            $table->decimal('po_det_discvalue', 15, 2)->default(0);
            $table->decimal('po_det_discamt', 15, 2)->default(0);

            $table->decimal('po_det_total', 15, 2)->default(0);

            // optional tapi sangat disarankan
            $table->index('po_det_mstrid');
            $table->index('po_det_productid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_det');
    }
};
