<?php

use App\Models\EmbuScore;

test('effective_score returns nilai_akhir if set', function () {
    $score = new EmbuScore([
        'nilai_akhir' => 75.5,
        'total_score' => 75.5,
        'denda' => 0.0,
    ]);

    expect($score->effective_score)->toBe(75.5);
});

test('effective_score returns total_score minus denda if total_score is set but nilai_akhir is zero', function () {
    $score = new EmbuScore([
        'nilai_akhir' => 0.0,
        'total_score' => 80.0,
        'denda' => 5.0,
    ]);

    expect($score->effective_score)->toBe(75.0);
});

test('effective_score calculates sum of entered scores on the fly when fewer than 5 judges have scored', function () {
    $score = new EmbuScore([
        'nilai_akhir' => 0.0,
        'total_score' => 0.0,
        'judge_1' => 0.0,
        'judge_2' => 50.0,
        'judge_3' => 0.0,
        'judge_4' => 0.0,
        'judge_5' => 0.0,
        'denda' => 0.0,
    ]);

    expect($score->effective_score)->toBe(50.0);
});

test('effective_score calculates middle 3 sum on the fly when all 5 judges have scored', function () {
    $score = new EmbuScore([
        'nilai_akhir' => 0.0,
        'total_score' => 0.0,
        'judge_1' => 50.0, // min (dropped)
        'judge_2' => 60.0, // middle
        'judge_3' => 70.0, // middle
        'judge_4' => 80.0, // middle
        'judge_5' => 90.0, // max (dropped)
        'denda' => 5.0,
    ]);

    // Middle 3 are 60, 70, 80. Sum = 210. Denda = 5. Effective = 205.
    expect($score->effective_score)->toBe(205.0);
});
