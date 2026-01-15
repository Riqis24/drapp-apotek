<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cust_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique()->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->date('date');
            $table->enum('method_payment', ['cash', 'credit']);
            $table->decimal('total', 15, 2);
            $table->decimal('paid', 15, 2)->default(0);
            $table->decimal('debt', 15, 2)->default(0);
            $table->unsignedBigInteger('status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cust_transactions');
    }
};
