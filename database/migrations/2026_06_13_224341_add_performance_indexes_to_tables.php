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
        Schema::table('drawing_match_numbers', function (Blueprint $table) {
            $table->index('match_number_id');
            $table->index('court_id');
            $table->index('rundown_id');
            $table->index('session_time_id');
            $table->index('registration_id');
            $table->index('draft_type');
            $table->index('round');
            $table->index(['match_number_id', 'round', 'pool_id']);
            $table->index(['match_number_id', 'court_id']);
        });

        Schema::table('match_numbers', function (Blueprint $table) {
            $table->index('age_group_id');
            $table->index('active_registration_id');
            $table->index('draft_type');
        });

        Schema::table('embu_scores', function (Blueprint $table) {
            $table->index(['match_number_id', 'round_label', 'registration_id']);
            $table->index('drawing_id');
        });

        Schema::table('randori_match_results', function (Blueprint $table) {
            $table->index(['match_number_id', 'bracket_node']);
        });

        Schema::table('schedule_referees', function (Blueprint $table) {
            $table->index(['court_id', 'rundown_id', 'session_time_id']);
            $table->index('referee_id');
        });

        Schema::table('active_court_referees', function (Blueprint $table) {
            $table->index('court_id');
            $table->index('referee_id');
        });

        Schema::table('schedule_paniteras', function (Blueprint $table) {
            $table->index(['court_id', 'rundown_id', 'session_time_id']);
        });

        Schema::table('referee_score_details', function (Blueprint $table) {
            $table->index(['match_number_id', 'referee_id', 'scorable_id', 'scorable_type']);
        });

        Schema::table('tournament_results', function (Blueprint $table) {
            $table->index('match_number_id');
        });

        Schema::table('athlete_match_number', function (Blueprint $table) {
            $table->index('match_number_id');
            $table->index('registration_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drawing_match_numbers', function (Blueprint $table) {
            $table->dropIndex(['match_number_id']);
            $table->dropIndex(['court_id']);
            $table->dropIndex(['rundown_id']);
            $table->dropIndex(['session_time_id']);
            $table->dropIndex(['registration_id']);
            $table->dropIndex(['draft_type']);
            $table->dropIndex(['round']);
            $table->dropIndex(['match_number_id', 'round', 'pool_id']);
            $table->dropIndex(['match_number_id', 'court_id']);
        });

        Schema::table('match_numbers', function (Blueprint $table) {
            $table->dropIndex(['age_group_id']);
            $table->dropIndex(['active_registration_id']);
            $table->dropIndex(['draft_type']);
        });

        Schema::table('embu_scores', function (Blueprint $table) {
            $table->dropIndex(['match_number_id', 'round_label', 'registration_id']);
            $table->dropIndex(['drawing_id']);
        });

        Schema::table('randori_match_results', function (Blueprint $table) {
            $table->dropIndex(['match_number_id', 'bracket_node']);
        });

        Schema::table('schedule_referees', function (Blueprint $table) {
            $table->dropIndex(['court_id', 'rundown_id', 'session_time_id']);
            $table->dropIndex(['referee_id']);
        });

        Schema::table('active_court_referees', function (Blueprint $table) {
            $table->dropIndex(['court_id']);
            $table->dropIndex(['referee_id']);
        });

        Schema::table('schedule_paniteras', function (Blueprint $table) {
            $table->dropIndex(['court_id', 'rundown_id', 'session_time_id']);
        });

        Schema::table('referee_score_details', function (Blueprint $table) {
            $table->dropIndex(['match_number_id', 'referee_id', 'scorable_id', 'scorable_type']);
        });

        Schema::table('tournament_results', function (Blueprint $table) {
            $table->dropIndex(['match_number_id']);
        });

        Schema::table('athlete_match_number', function (Blueprint $table) {
            $table->dropIndex(['match_number_id']);
            $table->dropIndex(['registration_id']);
        });
    }
};
