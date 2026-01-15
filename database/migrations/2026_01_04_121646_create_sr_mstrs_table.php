<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sr_mstr', function (Blueprint $table) {
            $table->id('sr_mstr_id');

            $table->string('sr_mstr_nbr')->nullable();
            $table->unsignedBigInteger('sr_mstr_smid');
            $table->unsignedBigInteger('sr_mstr_custid');
            $table->date('sr_mstr_date')->nullable();
            $table->text('sr_mstr_reason')->nullable();

            $table->unsignedBigInteger('sr_mstr_createdby');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sr_mstr');
    }
};
