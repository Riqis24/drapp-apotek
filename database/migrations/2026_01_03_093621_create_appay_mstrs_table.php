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
        Schema::create('appay_mstr', function (Blueprint $table) {
            $table->bigIncrements('appay_mstr_id');

            $table->string('appay_mstr_nbr')->unique();
            $table->date('appay_mstr_date');

            $table->unsignedBigInteger('appay_mstr_suppid');

            $table->decimal('appay_mstr_total', 18, 2)->default(0);

            $table->string('appay_mstr_method')->nullable(); // cash / transfer / bank / giro
            $table->string('appay_mstr_refno')->nullable();  // no transfer / giro

            $table->text('appay_mstr_note')->nullable();

            $table->unsignedBigInteger('appay_mstr_createdby');

            $table->timestamps();

            // FK (optional, tapi direkomendasikan)
            $table->foreign('appay_mstr_suppid')
                ->references('supp_mstr_id')
                ->on('supp_mstr');

            $table->foreign('appay_mstr_createdby')
                ->references('user_mstr_id')
                ->on('user_mstr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appay_mstr');
    }
};
