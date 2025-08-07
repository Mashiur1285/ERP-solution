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
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->foreignId('supplier_id')->constrained();
            $table->string('invoice_number', 32);
            $table->string('variant');
            $table->integer('cases_sold');
            $table->integer('bottles_per_case');
            $table->integer('purchased_bottles_sold')->default(0);
            $table->integer('free_bottles_sold')->default(0);
            $table->integer('total_bottles_sold')->default(0);
            $table->decimal('purchase_unit_price', 10, 2);
            $table->decimal('selling_price_per_case', 10, 2);
            $table->decimal('unit_price', 10, 2)->default(0.00); // Selling price per bottle
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->decimal('profit', 10, 2)->default(0.00);
            $table->string('status')->default(SalesItemsStatus::PENDING->value);
            $table->dateTime('delivery_date')->nullable();
            $table->timestamps();

            $table->index(['sale_id', 'variant']);
            $table->index(['product_id', 'variant']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
