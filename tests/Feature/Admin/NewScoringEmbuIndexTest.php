<?php

use App\Livewire\Admin\NewScoringEmbuIndex;
use App\Models\DrawingMatchNumber;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('callParticipant dispatches swal warning if registration is null', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create([
        'name' => 'Dewasa',
        'order' => 1,
        'price' => 0,
    ]);

    $matchNumber = MatchNumber::create([
        'name' => 'Embu Pasangan Yudansha',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    $drawing = DrawingMatchNumber::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => null, // Empty / placeholder final slot
        'round' => 'Final',
    ]);

    Livewire::actingAs($admin)
        ->test(NewScoringEmbuIndex::class, ['matchNumber' => $matchNumber])
        ->call('callParticipant', $drawing->id)
        ->assertDispatched('swal', function ($name, $params) {
            return isset($params[0]) &&
                ($params[0]['icon'] ?? null) === 'warning' &&
                str_contains($params[0]['title'] ?? '', 'Belum Tergenerate');
        });
});
