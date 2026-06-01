<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lift_items', function (Blueprint $table) {
            $table->decimal('number_of_cases', 10, 4)->change();
        });
    }

    public function down(): void
    {
        Schema::table('lift_items', function (Blueprint $table) {
            $table->integer('number_of_cases')->change();
        });
    }
};
