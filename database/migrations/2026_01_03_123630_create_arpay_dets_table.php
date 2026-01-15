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
        Schema::create('arpay_det', function (Blueprint $table) {
            $table->bigIncrements('arpay_det_id');

            $table->unsignedBigInteger('arpay_det_mstrid');
            $table->unsignedBigInteger('arpay_det_arid');

            $table->decimal('arpay_det_amount', 18, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arpay_dets');
    }
};
