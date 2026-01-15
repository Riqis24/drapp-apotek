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
        Schema::create('arpay_mstr', function (Blueprint $table) {
            $table->bigIncrements('arpay_mstr_id');

            $table->string('arpay_mstr_nbr')->unique();
            $table->date('arpay_mstr_date');
            $table->unsignedBigInteger('arpay_mstr_customerid');

            $table->decimal('arpay_mstr_amount', 18, 2);
            $table->string('arpay_mstr_method')->nullable();
            $table->string('arpay_mstr_ref')->nullable();

            $table->unsignedBigInteger('arpay_mstr_createdby')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arpay_mstr');
    }
};
