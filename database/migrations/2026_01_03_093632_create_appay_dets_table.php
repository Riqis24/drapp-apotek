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
        Schema::create('appay_det', function (Blueprint $table) {
            $table->bigIncrements('appay_det_id');

            $table->unsignedBigInteger('appay_det_mstrid'); //relasi ke appay_mstr
            $table->unsignedBigInteger('appay_det_apid'); //relasi ke ap_mstr

            $table->decimal('appay_det_payamount', 18, 2);

            $table->timestamps();

            $table->foreign('appay_det_mstrid')
                ->references('appay_mstr_id')
                ->on('appay_mstr')
                ->onDelete('cascade');

            $table->foreign('appay_det_apid')
                ->references('ap_mstr_id')
                ->on('ap_mstr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appay_det');
    }
};
