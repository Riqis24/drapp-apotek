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
        Schema::create('ts_mstr', function (Blueprint $table) {
            $table->id('ts_mstr_id');
            $table->string('ts_mstr_nbr')->unique();
            $table->date('ts_mstr_date');

            $table->unsignedBigInteger('ts_mstr_from');
            $table->unsignedBigInteger('ts_mstr_to');

            $table->enum('ts_mstr_status', ['draft', 'posted', 'cancelled'])
                ->default('draft');

            $table->text('ts_mstr_note')->nullable();
            $table->unsignedBigInteger('ts_mstr_createdby');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ts_mstr');
    }
};
