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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->unsignedBigInteger('loc_id')->nullable();
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->enum('type', ['in', 'out', 'adjustment']);
            $table->decimal('quantity', 18, 2);
            $table->text('note')->nullable();
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_transactions');
    }
};
