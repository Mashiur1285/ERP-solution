<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lifts', function (Blueprint $table) {
            $table->string('status', 20)->default('completed')->after('lift_number');
        });
    }

    public function down(): void
    {
        Schema::table('lifts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
