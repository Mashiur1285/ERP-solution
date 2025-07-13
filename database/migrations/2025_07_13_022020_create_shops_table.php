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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('shop_name', 30);
            $table->string('owner_name', 30)->nullable();
            $table->string('shop_address', 100)->nullable();
            $table->string('phone_number')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('website')->nullable();
            $table->string('national_id')->nullable();
            $table->string('trade_license')->nullable();
            $table->string('tax_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
