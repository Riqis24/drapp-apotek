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
        Schema::create('pr_det', function (Blueprint $table) {
            $table->id('pr_det_id');

            $table->unsignedBigInteger('pr_det_mstrid')->nullable();
            $table->unsignedBigInteger('pr_det_bpbdetid')->nullable();

            $table->unsignedBigInteger('pr_det_productid')->nullable();
            $table->unsignedBigInteger('pr_det_um')->nullable();
            $table->decimal('pr_det_umconv', 15, 4)->nullable();

            $table->decimal('pr_det_qty', 15, 4)->nullable();
            $table->decimal('pr_det_qtyconv', 15, 4)->nullable();

            $table->unsignedBigInteger('pr_det_batchid')->nullable();

            $table->decimal('pr_det_price', 15, 2)->default(0);
            $table->decimal('pr_det_subtotal', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr_det');
    }
};
