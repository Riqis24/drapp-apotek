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
        Schema::create('sa_mstr', function (Blueprint $table) {
            $table->id('sa_mstr_id');

            $table->string('sa_mstr_nbr')->nullable();
            $table->date('sa_mstr_date');
            $table->unsignedBigInteger('sa_mstr_locid');

            $table->string('sa_mstr_ref')->nullable(); // contoh: SO-0001
            $table->string('sa_mstr_reason')->nullable();

            $table->enum('sa_mstr_status', [
                'draft',
                'posted',
                'reversed'
            ])->default('draft');

            $table->unsignedBigInteger('sa_mstr_createdby');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sa_mstr');
    }
};
