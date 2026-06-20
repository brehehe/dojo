<?php

use App\Models\Court\Court;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;

test('monitor court state returns 200 with json', function () {
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);

    $response = $this->getJson("/api/svelte-monitor/court/{$court->id}/state");

    $response->assertSuccessful()
        ->assertJsonStructure([
            'court' => ['id', 'name'],
            'timer_state' => ['status', 'elapsed_ms'],
        ]);
});

test('monitor hasil court state returns 200 with json', function () {
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);

    $response = $this->getJson("/api/svelte-monitor/hasil/court/{$court->id}/state");

    $response->assertSuccessful()
        ->assertJsonStructure([
            'court',
            'match',
            'drawingData',
            'randoriResults',
            'embuRanking',
            'activeNodeKey',
        ]);
});

test('monitor hasil match state returns 200 with json', function () {
    $ageGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 1, 'price' => 0]);
    $matchNumber = MatchNumber::create([
        'name' => 'Embu Pasangan',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    $response = $this->getJson("/api/svelte-monitor/hasil/match/{$matchNumber->id}/state");

    $response->assertSuccessful()
        ->assertJsonStructure([
            'court',
            'match',
            'drawingData',
            'randoriResults',
            'embuRanking',
            'activeNodeKey',
        ]);
});

test('monitor referee court state returns 200 with json', function () {
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);

    $response = $this->getJson("/api/svelte-monitor/referee/court/{$court->id}/state");

    $response->assertSuccessful()
        ->assertJsonStructure([
            'court' => ['id', 'name'],
            'referees',
        ]);
});

test('monitor rekapitulasi hasil court state returns 200 with json', function () {
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);

    $response = $this->getJson("/api/svelte-monitor/rekapitulasi-hasil/court/{$court->id}/state");

    $response->assertSuccessful()
        ->assertJsonStructure([
            'court' => ['id', 'name'],
            'match',
            'scores',
            'currentRound',
            'poolName',
        ]);
});

test('monitor timer court state returns 200 with json', function () {
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);

    $response = $this->getJson("/api/svelte-monitor/timer/court/{$court->id}/state");

    $response->assertSuccessful()
        ->assertJsonStructure([
            'court' => ['id', 'name'],
            'timer_state' => ['status', 'elapsed_ms', 'server_time_ms'],
        ]);
});

test('court timer state endpoint returns 200 with json', function () {
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);

    $response = $this->getJson("/api/court/{$court->id}/timer-state");

    $response->assertSuccessful()
        ->assertJsonStructure([
            'status',
            'elapsed_ms',
            'server_time_ms',
        ]);
});
