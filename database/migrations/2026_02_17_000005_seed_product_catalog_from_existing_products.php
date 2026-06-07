<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Populate product_catalog from existing products (distinct by name + supplier)
        DB::statement("
            INSERT INTO product_catalog (name, supplier_id, category_id, brand_id, created_at, updated_at)
            SELECT DISTINCT ON (name, supplier_id)
                name, supplier_id, category_id, brand_id, NOW(), NOW()
            FROM products
            WHERE deleted_at IS NULL
            ORDER BY name, supplier_id, id DESC
            ON CONFLICT (name, supplier_id) DO NOTHING
        ");

        // Backfill products.product_catalog_id
        DB::statement("
            UPDATE products p
            SET product_catalog_id = pc.id
            FROM product_catalog pc
            WHERE p.name = pc.name AND p.supplier_id = pc.supplier_id
        ");
    }

    public function down(): void
    {
        DB::statement("UPDATE products SET product_catalog_id = NULL");
        DB::statement("DELETE FROM product_catalog");
    }
};
