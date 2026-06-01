<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_catalog', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('brand_id')->nullable()->constrained('brands');
            $table->jsonb('default_variants')->default('[]');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['name', 'supplier_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_catalog');
    }
};
