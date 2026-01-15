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
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('type', ['income', 'expense', 'liability']);
            $table->string('method')->nullable();
            $table->string('data_source');
            $table->text('description')->nullable();
            $table->decimal('amount', 18, 2);
            // $table->decimal('ppn', 18, 2);
            // $table->decimal('grandtotal', 18, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('financial_records');
    }
};
