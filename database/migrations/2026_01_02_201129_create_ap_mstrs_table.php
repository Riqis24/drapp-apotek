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
        Schema::create('ap_mstr', function (Blueprint $table) {
            $table->bigIncrements('ap_mstr_id');

            $table->string('ap_mstr_nbr')->unique();

            $table->string('ap_mstr_reftype');          // bpb
            $table->unsignedBigInteger('ap_mstr_refid'); // bpb_mstr_id

            $table->unsignedBigInteger('ap_mstr_suppid');

            $table->date('ap_mstr_date');
            $table->date('ap_mstr_duedate');

            $table->decimal('ap_mstr_amount', 18, 2);
            $table->decimal('ap_mstr_paid', 18, 2)->default(0);
            $table->decimal('ap_mstr_balance', 18, 2);

            $table->enum('ap_mstr_status', ['unpaid', 'partial', 'paid', 'credit'])->default('unpaid');

            $table->unsignedBigInteger('ap_mstr_createdby');
            $table->timestamps();

            $table->index(['ap_mstr_reftype', 'ap_mstr_refid']);
            $table->index('ap_mstr_suppid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ap_mstr');
    }
};
