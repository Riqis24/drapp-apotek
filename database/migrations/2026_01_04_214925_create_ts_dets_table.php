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
        Schema::create('ts_det', function (Blueprint $table) {
            $table->id('ts_det_id');
            $table->unsignedBigInteger('ts_det_mstrid');

            $table->unsignedBigInteger('ts_det_productid');
            $table->unsignedBigInteger('ts_det_batchid')->nullable();

            $table->string('ts_det_um');
            $table->decimal('ts_det_qty', 12, 2);
            // $table->decimal('ts_det_qtyconv', 12, 2);

            $table->string('ts_det_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ts_det');
    }
};
