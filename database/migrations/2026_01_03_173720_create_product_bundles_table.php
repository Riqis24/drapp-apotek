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
        Schema::create('product_bundle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bundle_product_id')->nullable();

            $table->unsignedBigInteger('product_measurement_id')->nullable();

            $table->decimal('quantity', 12, 4)->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_bundle');
    }
};
