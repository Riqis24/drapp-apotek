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
        Schema::create('cashier_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id'); // kasir
            $table->foreignId('loc_id'); // lokasi kasir
            $table->decimal('opening_amount', 15, 2)->default(0);
            $table->decimal('closing_amount', 15, 2)->nullable();
            $table->decimal('transactions_total', 15, 2)->default(0);
            $table->decimal('discrepancy', 15, 2)->default(0);
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashier_sessions');
    }
};
