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
        Schema::create('bpb_det', function (Blueprint $table) {
            $table->bigIncrements('bpb_det_id');

            $table->unsignedBigInteger('bpb_det_mstrid')->nullable();;
            $table->unsignedBigInteger('bpb_det_podetid')->nullable();;
            $table->unsignedBigInteger('bpb_det_productid')->nullable();;
            $table->unsignedBigInteger('bpb_det_locid')->nullable();;

            $table->decimal('bpb_det_qty', 15, 2);
            $table->unsignedBigInteger('bpb_det_um')->nullable();
            $table->decimal('bpb_det_umconv', 15, 2)->default(1);
            $table->decimal('bpb_det_qtyrcvd', 15, 2);
            $table->decimal('bpb_det_price', 15, 2)->default(0);
            $table->decimal('bpb_det_priceconv', 15, 2)->default(0);


            $table->string('bpb_det_disctype', 10)->nullable();
            $table->decimal('bpb_det_discvalue', 15, 2)->default(0);
            $table->decimal('bpb_det_discamt', 15, 2)->default(0);

            $table->decimal('bpb_det_total', 15, 2)->default(0);
            $table->unsignedBigInteger('bpb_det_updateprice')->default(0);

            // khusus apotek
            $table->string('bpb_det_batch', 50)->nullable();
            $table->date('bpb_det_expired')->nullable();
            $table->timestamps();

            $table->index('bpb_det_mstrid');
            $table->index('bpb_det_productid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bpb_det');
    }
};
