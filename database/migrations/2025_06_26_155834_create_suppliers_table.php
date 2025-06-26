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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name',40);
            $table->string('branch_name',20)->nullable();
            $table->string('phone_number',20)->unique();
            $table->string('emergency_phone_number',20)->nullable();
            $table->text('address');
            $table->string('email')->nullable();
            $table->string('country',20)->nullable();
            $table->string('city',20)->nullable();
            $table->string('website')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
