<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lift_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lift_id')->constrained('lifts')->onDelete('cascade');
            $table->foreignId('product_catalog_id')->constrained('product_catalog');
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->string('variant', 50);
            $table->integer('number_of_cases');
            $table->decimal('case_buying_price', 10, 2);
            $table->integer('bottles_per_case');
            $table->integer('free_bottles_per_case')->default(0);
            $table->integer('total_bottles');
            $table->integer('total_free_bottles')->default(0);
            $table->decimal('total_cost', 12, 2);
            $table->decimal('actual_rate_per_bottle', 10, 4);
            $table->integer('cases_with_free_bottles')->default(0);
            $table->integer('cases_without_free_bottles')->default(0);
            $table->integer('extra_free_bottles')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lift_items');
    }
};
