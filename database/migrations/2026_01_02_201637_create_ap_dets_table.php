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
        Schema::create('ap_det', function (Blueprint $table) {
            $table->bigIncrements('ap_det_id');

            $table->unsignedBigInteger('ap_det_mstrid');
            $table->date('ap_det_paydate');
            $table->decimal('ap_det_payamount', 18, 2);

            $table->enum('ap_det_paymethod', ['cash', 'bank', 'giro'])->default('cash');
            $table->text('ap_det_note')->nullable();

            $table->unsignedBigInteger('ap_det_createdby');
            $table->timestamps();

            $table->foreign('ap_det_mstrid')->references('ap_mstr_id')->on('ap_mstr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ap_det');
    }
};
