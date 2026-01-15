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
        Schema::create('batch_mstr', function (Blueprint $table) {
            $table->bigIncrements('batch_mstr_id');
            $table->unsignedBigInteger('batch_mstr_productid');
            $table->string('batch_mstr_no', 50);
            $table->date('batch_mstr_expireddate');
            $table->timestamps();

            $table->foreign('batch_mstr_productid')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_mstr');
    }
};
