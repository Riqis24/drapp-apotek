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
        Schema::create('sales_mstr', function (Blueprint $table) {
            $table->bigIncrements('sales_mstr_id');

            $table->string('sales_mstr_nbr', 50)->unique();
            $table->date('sales_mstr_date');

            $table->unsignedBigInteger('sales_mstr_custid')->nullable();
            $table->unsignedBigInteger('sales_mstr_locid');
            $table->unsignedBigInteger('sales_mstr_pmid')->nullable();


            $table->decimal('sales_mstr_subtotal', 15, 2)->default(0);
            $table->decimal('sales_mstr_discamt', 15, 2)->default(0);
            $table->decimal('sales_mstr_ppnamt', 15, 2)->default(0);
            $table->decimal('sales_mstr_grandtotal', 15, 2)->default(0);
            $table->decimal('sales_mstr_paidamt', 15, 2)->default(0);
            $table->decimal('sales_mstr_changeamt', 15, 2)->default(0);

            $table->enum('sales_mstr_paymenttype', ['cash', 'credit'])->default('cash');
            $table->string('sales_mstr_paymentmethod')->nullable();
            $table->enum('sales_mstr_status', ['draft', 'posted', 'void'])->default('draft');

            $table->text('sales_mstr_note')->nullable();

            // custom timestamp
            $table->unsignedBigInteger('sales_mstr_createdby')->nullable();
            $table->timestamp('sales_mstr_createdat')->nullable();
            $table->timestamp('sales_mstr_updatedat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_mstr');
    }
};
