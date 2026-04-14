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
        Schema::table('match_numbers', function (Blueprint $table) {
            $table->boolean('is_active')->default(false)->after('drawing_generated_at');
            $table->string('active_bracket_node')->nullable()->after('is_active');
        });

        Schema::table('match_number_referee', function (Blueprint $table) {
            $table->integer('judge_index')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_numbers', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'active_bracket_node']);
        });

        Schema::table('match_number_referee', function (Blueprint $table) {
            $table->dropColumn('judge_index');
        });
    }
};
