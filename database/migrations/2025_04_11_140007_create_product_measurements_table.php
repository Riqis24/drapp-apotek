<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('product_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('measurement_id')->constrained()->onDelete('cascade');
            $table->foreignId('placement_id')->nullable();
            $table->decimal('conversion', 18, 6)->default(1);
            $table->decimal('last_buy_price', 18, 6)->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_measurements');
    }
};
