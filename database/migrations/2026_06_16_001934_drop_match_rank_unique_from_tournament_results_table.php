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
        Schema::table('tournament_results', function (Blueprint $table) {
            $table->dropUnique('tournament_results_match_rank_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournament_results', function (Blueprint $table) {
            $table->unique(['match_number_id', 'rank'], 'tournament_results_match_rank_unique');
        });
    }
};
