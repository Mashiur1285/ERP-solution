<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the materialized view for shop dues
        DB::statement('
            CREATE MATERIALIZED VIEW shop_dues_summary AS
            SELECT
                s.id as shop_id,
                s.shop_name,
                s.owner_name,
                s.phone_number,
                COALESCE(SUM(sales.due_amount), 0) as total_due,
                COALESCE(SUM(sales.total_amount), 0) as total_sales,
                COALESCE(SUM(sales.paid_amount), 0) as total_paid,
                COUNT(sales.id) as total_transactions,
                COUNT(CASE WHEN sales.due_amount > 0 THEN 1 END) as pending_dues_count,
                MAX(sales.sale_date) as last_sale_date,
                NOW() as last_updated
            FROM shops s
            LEFT JOIN sales ON s.id = sales.shop_id
            GROUP BY s.id, s.shop_name, s.owner_name, s.phone_number
        ');

        // Create unique index on shop_id for concurrent refresh
        DB::statement('CREATE UNIQUE INDEX idx_shop_dues_summary_shop_id ON shop_dues_summary (shop_id)');

        // Create index on total_due for sorting/filtering
        DB::statement('CREATE INDEX idx_shop_dues_summary_total_due ON shop_dues_summary (total_due)');

        // Create index on shop_name for searching
        DB::statement('CREATE INDEX idx_shop_dues_summary_shop_name ON shop_dues_summary (shop_name)');

        // Create trigger function for auto-refresh
        DB::statement('
            CREATE OR REPLACE FUNCTION refresh_shop_dues_summary()
            RETURNS TRIGGER AS $$
            BEGIN
                REFRESH MATERIALIZED VIEW CONCURRENTLY shop_dues_summary;
                RETURN NULL;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // Trigger on sales table
        DB::statement('
            CREATE TRIGGER refresh_view_on_sales_change
            AFTER INSERT OR UPDATE OR DELETE ON sales
            FOR EACH STATEMENT
            EXECUTE FUNCTION refresh_shop_dues_summary();
        ');

        // Optional: Trigger on shops table (if shop data like shop_name changes)
        DB::statement('
            CREATE TRIGGER refresh_view_on_shops_change
            AFTER INSERT OR UPDATE OR DELETE ON shops
            FOR EACH STATEMENT
            EXECUTE FUNCTION refresh_shop_dues_summary();
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers and function
        DB::statement('DROP TRIGGER IF EXISTS refresh_view_on_sales_change ON sales');
        DB::statement('DROP TRIGGER IF EXISTS refresh_view_on_shops_change ON shops');
        DB::statement('DROP FUNCTION IF EXISTS refresh_shop_dues_summary');

        // Drop materialized view and indexes
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS shop_dues_summary');
        DB::statement('DROP INDEX IF EXISTS idx_shop_dues_summary_shop_id');
        DB::statement('DROP INDEX IF EXISTS idx_shop_dues_summary_total_due');
        DB::statement('DROP INDEX IF EXISTS idx_shop_dues_summary_shop_name');
    }
};
