<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\SalesItemsStatus;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id');
            $table->foreignId('product_id');
            $table->foreignId('suppler_id');
            $table->string('invoice_number', 32);
            $table->string('variant');
            $table->integer('boxes_sold');
            $table->integer('bottles_per_box');
            $table->integer('free_bottles');
            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->integer('quantity');
            $table->string('status')->default(SalesItemsStatus::PENDING->value);
            $table->dateTime('delivery_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
