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
        Schema::create('sa_det', function (Blueprint $table) {
            $table->id('sa_det_id');

            $table->unsignedBigInteger('sa_det_mstrid');

            $table->unsignedBigInteger('sa_det_productid');
            $table->unsignedBigInteger('sa_det_batchid')->nullable();

            $table->decimal('sa_det_qtysystem', 15, 4);
            $table->decimal('sa_det_qtyphysical', 15, 4);
            $table->decimal('sa_det_qtydiff', 15, 4);

            $table->text('sa_det_note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sa_det');
    }
};
