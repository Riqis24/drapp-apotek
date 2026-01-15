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
        Schema::create('product_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('cust_transactions')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('measurement_id')->constrained('measurements');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_transactions');
    }
};
