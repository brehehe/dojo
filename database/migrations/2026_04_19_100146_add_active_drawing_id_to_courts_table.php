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
        Schema::table('courts', function (Blueprint $table) {
            $table->foreignId('active_drawing_id')
                ->nullable()
                ->after('active_bracket_node')
                ->constrained('drawing_match_numbers')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courts', function (Blueprint $table) {
            $table->dropForeign(['active_drawing_id']);
            $table->dropColumn('active_drawing_id');
        });
    }
};
