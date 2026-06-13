<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $merges = [
            [
                'target_id' => 51,
                'target_name' => 'Embu Pasangan putra/putri/campuran Kyu kenshi',
                'source_ids' => [49, 50],
            ],
            [
                'target_id' => 34,
                'target_name' => 'Embu Beregu putra/putri/campuran eksebisi',
                'source_ids' => [32, 33],
            ],
            [
                'target_id' => 13,
                'target_name' => 'Embu Beregu putra/putri/campuran eksebisi',
                'source_ids' => [11, 12],
            ],
            [
                'target_id' => 10,
                'target_name' => 'Embu Pasangan putra/putri/campuran Kyu kenshi eksebisi',
                'source_ids' => [8, 9],
            ],
            [
                'target_id' => 4,
                'target_name' => 'Embu Pasangan putra/putri/campuran Kyu kenshi eksebisi',
                'source_ids' => [5],
            ],
        ];

        foreach ($merges as $merge) {
            $targetId = $merge['target_id'];
            $sourceIds = $merge['source_ids'];
            $targetName = $merge['target_name'];

            // 1. Update target match number name and gender
            DB::table('match_numbers')
                ->where('id', $targetId)
                ->update([
                    'name' => $targetName,
                    'gender' => 'Mix',
                ]);

            // 2. Move drawing records from source to target
            $allIds = array_merge([$targetId], $sourceIds);
            DB::table('embu_scores')->whereIn('match_number_id', $allIds)->delete();
            DB::table('referee_score_details')->whereIn('match_number_id', $allIds)->delete();
            DB::table('randori_match_results')->whereIn('match_number_id', $allIds)->delete();
            DB::table('tournament_results')->whereIn('match_number_id', $allIds)->delete();
            DB::table('randori_judge_scores')->whereIn('match_number_id', $allIds)->delete();
            DB::table('embu_champions')->whereIn('match_number_id', $allIds)->delete();

            foreach ($sourceIds as $sourceId) {
                DB::table('drawing_match_numbers')
                    ->where('match_number_id', $sourceId)
                    ->update(['match_number_id' => $targetId]);
            }

            // 3. Retrieve and regroup registrations to keep teams consecutive
            $registrations = DB::table('athlete_match_number')
                ->whereIn('match_number_id', $allIds)
                ->orderBy('registration_id')
                ->orderBy('match_number_id') // Group by original match_number_id (team)
                ->orderBy('id')
                ->get();

            // Delete existing rows
            DB::table('athlete_match_number')->whereIn('match_number_id', $allIds)->delete();

            // Re-insert in consecutive order
            foreach ($registrations as $reg) {
                DB::table('athlete_match_number')->insert([
                    'athlete_id' => $reg->athlete_id,
                    'match_number_id' => $targetId, // Unified ID
                    'registration_id' => $reg->registration_id,
                    'technique_ids' => $reg->technique_ids,
                    'created_at' => $reg->created_at,
                    'updated_at' => $reg->updated_at,
                ]);
            }

            // 5. Delete source match numbers from match_numbers
            DB::table('match_numbers')->whereIn('id', $sourceIds)->delete();
        }

        // 6. Delete obsolete MatchNumberMerge configurations
        $mergeIds = [1, 2, 3, 4, 5];
        DB::table('match_number_merge_details')->whereIn('match_number_merge_id', $mergeIds)->delete();
        DB::table('match_number_merges')->whereIn('id', $mergeIds)->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting a destructive merge migration is not supported.
    }
};
