<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * Shops can now be quick-created from the sale screen with just a name,
     * so a phone number is optional. The unique index stays: Postgres allows
     * multiple NULLs in a unique index, and blank input is stored as NULL.
     *
     * Raw SQL rather than `$table->string(...)->nullable()->change()`: Laravel's
     * change() re-issues the column TYPE as well, and Postgres refuses to alter
     * the type of a column the `shop_dues_summary` materialized view reads
     * ("cannot alter type of a column used by a view or rule"). Dropping the
     * NOT NULL on its own is allowed while the matview exists.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE shops ALTER COLUMN phone_number DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * Fails if any shop has a NULL phone_number by then — clear those rows first.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE shops ALTER COLUMN phone_number SET NOT NULL');
    }
};
