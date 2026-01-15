<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable(); // simpan path file logo
            $table->string('npwp')->nullable();
            $table->string('owner')->nullable();
            $table->text('footer_note')->nullable(); // untuk catatan di invoice
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_profiles');
    }
};
