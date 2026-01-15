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
        Schema::create('po_mstr', function (Blueprint $table) {
            $table->bigIncrements('po_mstr_id');

            $table->string('po_mstr_nbr', 50)->unique();
            $table->unsignedBigInteger('po_mstr_suppid');

            $table->string('po_mstr_payment', 50)->nullable();
            $table->date('po_mstr_duedate')->nullable();

            $table->date('po_mstr_date');
            $table->date('po_mstr_eta')->nullable();

            $table->string('po_mstr_status', 20)->default('draft');

            $table->decimal('po_mstr_subtotal', 15, 2)->default(0);

            $table->string('po_mstr_disctype', 10)->nullable(); // percent | amount
            $table->decimal('po_mstr_discvalue', 15, 2)->default(0);
            $table->decimal('po_mstr_discamt', 15, 2)->default(0);

            $table->string('po_mstr_ppntype', 10)->default('none'); // include | exclude | none
            $table->decimal('po_mstr_ppnrate', 5, 2)->default(0);
            $table->decimal('po_mstr_ppnamt', 15, 2)->default(0);

            $table->decimal('po_mstr_grandtotal', 15, 2)->default(0);

            $table->text('po_mstr_note')->nullable();

            $table->unsignedBigInteger('po_mstr_createdby');
            $table->unsignedBigInteger('po_mstr_approvedby')->nullable();

            $table->timestamp('po_mstr_createdat')->useCurrent();
            $table->timestamp('po_mstr_updatedat')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_mstr');
    }
};
