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
        Schema::create('so_det', function (Blueprint $table) {
            $table->id('so_det_id');

            $table->unsignedBigInteger('so_det_mstrid');

            $table->unsignedBigInteger('so_det_productid');
            $table->unsignedBigInteger('so_det_batchid')->nullable();

            // snapshot saat opname dibuat
            $table->decimal('so_det_qtysystem', 15, 4);

            // input user
            $table->decimal('so_det_qtyphysical', 15, 4)->nullable();

            $table->text('so_det_note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('so_dets');
    }
};
