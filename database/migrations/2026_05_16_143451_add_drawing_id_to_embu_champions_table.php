<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('embu_champions', function (Blueprint $table) {
            $table->foreignId('drawing_id')->nullable()->constrained('drawing_match_numbers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('embu_champions', function (Blueprint $table) {
            $table->dropForeign(['drawing_id']);
            $table->dropColumn('drawing_id');
        });
    }
};
