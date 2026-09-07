<?php

use App\Http\Requests\Monitor\ActivateMatchRequest;
use App\Http\Requests\Monitor\SaveRefereeAssignmentRequest;
use App\Http\Requests\Referee\SaveRefereeScoreRequest;
use App\Http\Requests\Referee\SubmitRefereeScoreRequest;
use App\Models\Court\Court;
use App\Models\User;
use App\Services\CourtMonitorService;
use App\Services\RefereeScoringService;
use Illuminate\Support\Facades\Validator;

test('CourtMonitorService getCourtsWithReferees returns courts with eager loaded referees', function () {
    $court = Court::firstOrCreate(
        ['name' => 'Court Test Refactor'],
        ['order' => 999]
    );

    $service = app(CourtMonitorService::class);
    $courts = $service->getCourtsWithReferees($court->id);

    expect($courts)->not->toBeEmpty();
    expect($courts->first()->id)->toBe($court->id);
    expect($courts->first()->relationLoaded('activeCourtReferees'))->toBeTrue();
    expect($courts->first()->current_referees)->not->toBeNull();
});

test('SaveRefereeScoreRequest validates input types', function () {
    $request = new SaveRefereeScoreRequest;

    $invalid = Validator::make([
        'embuItems' => 'not-an-array',
        'notes' => str_repeat('a', 1001),
    ], $request->rules());

    expect($invalid->fails())->toBeTrue();
    expect($invalid->errors()->has('embuItems'))->toBeTrue();
    expect($invalid->errors()->has('notes'))->toBeTrue();

    $valid = Validator::make([
        'embuItems' => [1 => 8, 2 => 9],
        'notes' => 'Good performance',
    ], $request->rules());

    expect($valid->passes())->toBeTrue();
});

test('SubmitRefereeScoreRequest requires signature', function () {
    $request = new SubmitRefereeScoreRequest;
    $validator = Validator::make([], $request->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('signature'))->toBeTrue();

    $valid = Validator::make([
        'signature' => 'data:image/png;base64,iVBORw0KGgo...',
        'embuItems' => [1 => 8, 2 => 8],
    ], $request->rules());

    expect($valid->passes())->toBeTrue();
});

test('SaveRefereeAssignmentRequest enforces exactly 5 referees', function () {
    $request = new SaveRefereeAssignmentRequest;

    $invalid4Referees = Validator::make([
        'court_id' => 1,
        'rundown_id' => 1,
        'session_time_id' => 1,
        'referees' => [1, 2, 3, 4],
    ], $request->rules());

    expect($invalid4Referees->fails())->toBeTrue();
    expect($invalid4Referees->errors()->has('referees'))->toBeTrue();

    $valid5Referees = Validator::make([
        'court_id' => 1,
        'rundown_id' => 1,
        'session_time_id' => 1,
        'referees' => [1, 2, 3, 4, 5],
    ], $request->rules());

    expect($valid5Referees->passes())->toBeTrue();
});

test('ActivateMatchRequest validates drawing_id exists', function () {
    $request = new ActivateMatchRequest;
    $validator = Validator::make([], $request->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('drawing_id'))->toBeTrue();
});

test('RefereeScoringController endpoints require authentication and validation', function () {
    $saveResponse = $this->postJson(route('admin.referee.scoring.save'), []);
    // Unauthorized without login
    $saveResponse->assertStatus(401);

    $submitResponse = $this->postJson(route('admin.referee.scoring.submit'), []);
    $submitResponse->assertStatus(401);
});

test('RefereeScoringService resolves referee state safely when no assignment exists', function () {
    $user = new User([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
    $user->id = 99999;

    $service = app(RefereeScoringService::class);
    $result = $service->resolveRefereeState($user);

    expect($result)->toBeArray();
    expect($result['activeMatch'])->toBeNull();
    expect($result['assignedCourt'])->toBeNull();
});
