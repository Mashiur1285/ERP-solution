<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lift_items', function (Blueprint $table) {
            $table->decimal('free_bottles_per_case', 10, 4)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('lift_items', function (Blueprint $table) {
            $table->integer('free_bottles_per_case')->default(0)->change();
        });
    }
};
