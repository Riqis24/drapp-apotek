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
        Schema::create('ar_mstr', function (Blueprint $table) {
            $table->bigIncrements('ar_mstr_id');

            $table->string('ar_mstr_nbr')->unique();

            $table->unsignedBigInteger('ar_mstr_salesid');
            $table->unsignedBigInteger('ar_mstr_customerid');

            $table->date('ar_mstr_date');       // tanggal sales
            $table->date('ar_mstr_duedate');     // jatuh tempo

            $table->decimal('ar_mstr_amount', 18, 2);   // total piutang
            $table->decimal('ar_mstr_paid', 18, 2)->default(0);
            $table->decimal('ar_mstr_balance', 18, 2);

            $table->enum('ar_mstr_status', ['unpaid', 'partial', 'paid'])
                ->default('unpaid');

            $table->timestamps();

            // FK (opsional enforce)
            // $table->foreign('ar_mstr_salesid')->references('sales_mstr_id')->on('sales_mstr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ar_mstr');
    }
};
