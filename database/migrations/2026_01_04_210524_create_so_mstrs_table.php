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
        Schema::create('so_mstr', function (Blueprint $table) {
            $table->id('so_mstr_id');

            $table->string('so_mstr_nbr')->nullable();
            $table->date('so_mstr_date');
            $table->unsignedBigInteger('so_mstr_locid');

            $table->enum('so_mstr_status', [
                'draft',
                'submitted',
                'approved'
            ])->default('draft');

            $table->text('so_mstr_note')->nullable();

            $table->unsignedBigInteger('so_mstr_createdby');
            $table->unsignedBigInteger('so_mstr_approvedby')->nullable();
            $table->timestamp('so_mstr_approvedate')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('so_mstr');
    }
};
