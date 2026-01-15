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
        Schema::create('loc_mstr', function (Blueprint $table) {
            $table->id('loc_mstr_id');
            $table->string('loc_mstr_code', 20)->unique();
            $table->string('loc_mstr_name');
            $table->boolean('loc_mstr_active')->default(true);
            $table->boolean('loc_mstr_isvisible')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loc_mstr');
    }
};
