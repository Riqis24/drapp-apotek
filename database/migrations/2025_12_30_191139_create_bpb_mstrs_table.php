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
        Schema::create('bpb_mstr', function (Blueprint $table) {
            $table->bigIncrements('bpb_mstr_id');

            $table->string('bpb_mstr_nbr', 50)->unique();
            $table->unsignedBigInteger('bpb_mstr_poid')->nullable();
            $table->unsignedBigInteger('bpb_mstr_suppid')->nullable();
            $table->unsignedBigInteger('bpb_mstr_locid')->nullable();

            $table->date('bpb_mstr_date');
            $table->string('bpb_mstr_status', 20)->default('draft');

            $table->decimal('bpb_mstr_subtotal', 15, 2)->default(0);
            $table->decimal('bpb_mstr_dpp', 15, 2)->default(0);

            $table->string('bpb_mstr_nofaktur')->nullable();
            $table->string('bpb_mstr_nosj')->nullable();
            $table->string('bpb_mstr_payment', 50)->nullable();
            $table->date('bpb_mstr_duedate')->nullable();

            $table->string('bpb_mstr_disctype', 10)->nullable();
            $table->decimal('bpb_mstr_discvalue', 15, 2)->default(0);
            $table->decimal('bpb_mstr_discamt', 15, 2)->default(0);

            $table->string('bpb_mstr_ppntype', 10)->default('none');
            $table->decimal('bpb_mstr_ppnrate', 5, 2)->default(0);
            $table->decimal('bpb_mstr_ppnamt', 15, 2)->default(0);

            $table->decimal('bpb_mstr_grandtotal', 15, 2)->default(0);

            $table->text('bpb_mstr_note')->nullable();

            $table->unsignedBigInteger('bpb_mstr_createdby');
            $table->timestamp('bpb_mstr_createdat')->useCurrent();
            $table->timestamp('bpb_mstr_updatedat')->nullable()->useCurrentOnUpdate();

            $table->index('bpb_mstr_poid');
            $table->index('bpb_mstr_suppid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bpb_mstr');
    }
};
