<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('match_numbers', function (Blueprint $table) {
            $table->json('drawing_data')->nullable()->after('order');
            $table->timestamp('drawing_generated_at')->nullable()->after('drawing_data');
        });
    }

    public function down(): void
    {
        Schema::table('match_numbers', function (Blueprint $table) {
            $table->dropColumn(['drawing_data', 'drawing_generated_at']);
        });
    }
};
