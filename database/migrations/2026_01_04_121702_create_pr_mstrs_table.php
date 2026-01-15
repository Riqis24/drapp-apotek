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
        Schema::create('pr_mstr', function (Blueprint $table) {
            $table->id('pr_mstr_id');

            $table->string('pr_mstr_nbr')->nullable();
            $table->unsignedBigInteger('pr_mstr_poid');
            $table->unsignedBigInteger('pr_mstr_bpbid');
            $table->unsignedBigInteger('pr_mstr_suppid');
            $table->date('pr_mstr_date')->nullable();
            $table->text('pr_mstr_reason')->nullable();

            $table->unsignedBigInteger('pr_mstr_createdby');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr_mstrs');
    }
};
