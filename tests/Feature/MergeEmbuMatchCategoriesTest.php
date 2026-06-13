<?php

use App\Models\MatchNumber\MatchNumber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('it successfully merges categories and updates registrations', function () {
    // 1. Manually insert the pre-merge records since RefreshDatabase runs migrations on an empty DB
    // First, let's clear the match_numbers table to avoid ID conflicts
    DB::table('athlete_match_number')->delete();
    DB::table('match_numbers')->delete();
    DB::table('age_groups')->delete();
    DB::table('registrations')->delete();
    DB::table('contingents')->delete();
    DB::table('athletes')->delete();

    // Insert mock age groups to satisfy foreign key constraints
    DB::table('age_groups')->insert([
        ['id' => 1, 'name' => 'Pemula', 'price' => 400000, 'order' => 1],
        ['id' => 2, 'name' => 'Remaja A', 'price' => 500000, 'order' => 2],
        ['id' => 3, 'name' => 'Remaja B', 'price' => 500000, 'order' => 3],
        ['id' => 4, 'name' => 'Dewasa', 'price' => 500000, 'order' => 4],
    ]);

    // Insert mock categories matching the IDs we merge
    DB::table('match_numbers')->insert([
        ['id' => 51, 'name' => 'Embu Pasangan Kyu kenshi (Campuran)', 'gender' => 'Mix', 'age_group_id' => 4, 'draft_type' => 'embu', 'max_athletes' => 2, 'order' => 1],
        ['id' => 50, 'name' => 'Embu Pasangan Kyu kenshi (Putri)', 'gender' => 'Female', 'age_group_id' => 4, 'draft_type' => 'embu', 'max_athletes' => 2, 'order' => 1],
        ['id' => 49, 'name' => 'Embu Pasangan Kyu kenshi (Putra)', 'gender' => 'Male', 'age_group_id' => 4, 'draft_type' => 'embu', 'max_athletes' => 2, 'order' => 1],

        ['id' => 34, 'name' => 'Embu Beregu eksebisi (Campuran)', 'gender' => 'Mix', 'age_group_id' => 3, 'draft_type' => 'embu', 'max_athletes' => 4, 'order' => 1],
        ['id' => 32, 'name' => 'Embu Beregu eksebisi (Putra)', 'gender' => 'Male', 'age_group_id' => 3, 'draft_type' => 'embu', 'max_athletes' => 4, 'order' => 1],
        ['id' => 33, 'name' => 'Embu Beregu eksebisi (Putri)', 'gender' => 'Female', 'age_group_id' => 3, 'draft_type' => 'embu', 'max_athletes' => 4, 'order' => 1],

        ['id' => 13, 'name' => 'Embu Beregu eksebisi (Campuran)', 'gender' => 'Mix', 'age_group_id' => 2, 'draft_type' => 'embu', 'max_athletes' => 4, 'order' => 1],
        ['id' => 11, 'name' => 'Embu Beregu eksebisi (Putra)', 'gender' => 'Male', 'age_group_id' => 2, 'draft_type' => 'embu', 'max_athletes' => 4, 'order' => 1],
        ['id' => 12, 'name' => 'Embu Beregu eksebisi (Putri)', 'gender' => 'Female', 'age_group_id' => 2, 'draft_type' => 'embu', 'max_athletes' => 4, 'order' => 1],

        ['id' => 10, 'name' => 'Embu Pasangan Kyu kenshi eksebisi (Campuran)', 'gender' => 'Mix', 'age_group_id' => 2, 'draft_type' => 'embu', 'max_athletes' => 2, 'order' => 1],
        ['id' => 8, 'name' => 'Embu Pasangan Kyu kenshi eksebisi (Putra)', 'gender' => 'Male', 'age_group_id' => 2, 'draft_type' => 'embu', 'max_athletes' => 2, 'order' => 1],
        ['id' => 9, 'name' => 'Embu Pasangan Kyu kenshi eksebisi (Putri)', 'gender' => 'Female', 'age_group_id' => 2, 'draft_type' => 'embu', 'max_athletes' => 2, 'order' => 1],

        ['id' => 4, 'name' => 'Embu Pasangan Kyu kenshi eksebisi (Putra)', 'gender' => 'Male', 'age_group_id' => 1, 'draft_type' => 'embu', 'max_athletes' => 2, 'order' => 1],
        ['id' => 5, 'name' => 'Embu Pasangan Kyu kenshi eksebisi (Putri)', 'gender' => 'Female', 'age_group_id' => 1, 'draft_type' => 'embu', 'max_athletes' => 2, 'order' => 1],
    ]);

    // Insert mock contingent, registration and athletes
    $contingentId = DB::table('contingents')->insertGetId([
        'name' => 'Test Contingent',
        'kab_kota' => 'Test City',
        'leader_name' => 'Test Leader',
    ]);

    DB::table('registrations')->insert([
        ['id' => 100, 'contingent_id' => $contingentId, 'status' => 'verified', 'athlete_status' => 'verified'],
    ]);

    DB::table('athletes')->insert([
        ['id' => 1, 'name' => 'Kenshi 1', 'gender' => 'Male', 'bpjs_status' => 'Aktif', 'birth_date' => '2000-01-01'],
        ['id' => 2, 'name' => 'Kenshi 2', 'gender' => 'Male', 'bpjs_status' => 'Aktif', 'birth_date' => '2000-01-01'],
    ]);

    // Insert mock athlete_match_number entries
    DB::table('athlete_match_number')->insert([
        // Athlete registered in both 51 and 50
        ['athlete_id' => 1, 'match_number_id' => 51, 'registration_id' => 100],
        ['athlete_id' => 1, 'match_number_id' => 50, 'registration_id' => 100],
        // Another athlete registered in only 49
        ['athlete_id' => 2, 'match_number_id' => 49, 'registration_id' => 100],
    ]);

    // Insert mock drawing entries
    DB::table('drawing_match_numbers')->insert([
        ['match_number_id' => 51, 'round' => 'Final', 'sequence_number' => 1, 'draft_type' => 'embu'],
        ['match_number_id' => 50, 'round' => 'Final', 'sequence_number' => 2, 'draft_type' => 'embu'],
    ]);

    // Insert mock merges
    DB::table('match_number_merges')->insert([
        ['id' => 1, 'name' => 'EMBU PASANGAN PUTRA/PUTRI/CAMPURAN PEMULA KYU KENSHI', 'age_group_id' => 1, 'type' => 'embu'],
        ['id' => 2, 'name' => 'Embu Pasangan eksebisi (Putra/Putri/Campuran)', 'age_group_id' => 2, 'type' => 'embu'],
        ['id' => 3, 'name' => 'Embu Beregu eksebisi (Putra/Putri/Campuran)', 'age_group_id' => 2, 'type' => 'embu'],
        ['id' => 4, 'name' => 'Embu Beregu eksebisi (Putra/Putri/Campuran)', 'age_group_id' => 3, 'type' => 'embu'],
        ['id' => 5, 'name' => 'Embu Pasangan Kyu kenshi (Putra/Putri/Campuran)', 'age_group_id' => 4, 'type' => 'embu'],
    ]);

    DB::table('match_number_merge_details')->insert([
        ['match_number_merge_id' => 5, 'match_number_id' => 51],
        ['match_number_merge_id' => 5, 'match_number_id' => 50],
    ]);

    // 2. Instantiate and call the migration up() method directly
    $migration = require database_path('migrations/2026_06_13_030441_merge_embu_match_categories.php');
    $migration->up();

    // 3. Assert target categories exist with updated names and correct Mix gender
    $mergedCategories = [
        4 => 'Embu Pasangan putra/putri/campuran Kyu kenshi eksebisi',
        10 => 'Embu Pasangan putra/putri/campuran Kyu kenshi eksebisi',
        13 => 'Embu Beregu putra/putri/campuran eksebisi',
        34 => 'Embu Beregu putra/putri/campuran eksebisi',
        51 => 'Embu Pasangan putra/putri/campuran Kyu kenshi',
    ];

    foreach ($mergedCategories as $id => $name) {
        $category = MatchNumber::find($id);
        expect($category)->not->toBeNull()
            ->and($category->name)->toBe($name)
            ->and($category->gender)->toBe('Mix');
    }

    // Assert source categories do not exist anymore
    $deletedIds = [5, 8, 9, 11, 12, 32, 33, 49, 50];
    foreach ($deletedIds as $id) {
        $category = MatchNumber::find($id);
        expect($category)->toBeNull();
    }

    // Assert registrations were moved and duplicate registration was resolved
    $registrations = DB::table('athlete_match_number')->where('registration_id', 100)->get();

    // Athlete 1 should keep both of their registrations under target category 51 (so they are separated into their respective teams/groups of 4)
    $athlete1Regs = $registrations->where('athlete_id', 1)->where('match_number_id', 51);
    expect($athlete1Regs->count())->toBe(2);

    // Athlete 2 should have 1 registration under target category 51
    $athlete2Regs = $registrations->where('athlete_id', 2)->where('match_number_id', 51);
    expect($athlete2Regs->count())->toBe(1);

    // Assert drawings were moved successfully
    $drawings = DB::table('drawing_match_numbers')->get();
    expect($drawings->where('match_number_id', 51)->count())->toBe(2);

    // Assert obsolete merges and merge details are deleted
    expect(DB::table('match_number_merges')->whereIn('id', [1, 2, 3, 4, 5])->count())->toBe(0);
    expect(DB::table('match_number_merge_details')->whereIn('match_number_merge_id', [1, 2, 3, 4, 5])->count())->toBe(0);
});
