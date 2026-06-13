<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('match_numbers')
            ->whereIn('id', [13, 34])
            ->update([
                'max_athletes' => 4,
                'drawing_generated_at' => null,
                'drawing_data' => null,
            ]);

        DB::table('drawing_match_numbers')
            ->whereIn('match_number_id', [13, 34])
            ->delete();

        DB::table('embu_scores')->whereIn('match_number_id', [13, 34])->delete();
        DB::table('referee_score_details')->whereIn('match_number_id', [13, 34])->delete();
        DB::table('embu_champions')->whereIn('match_number_id', [13, 34])->delete();
        DB::table('tournament_results')->whereIn('match_number_id', [13, 34])->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('match_numbers')
            ->whereIn('id', [13, 34])
            ->update([
                'max_athletes' => 8,
            ]);
    }
};
