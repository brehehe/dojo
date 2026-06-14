<?php

use App\Events\CourtUpdated;
use App\Events\MatchUpdated;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

test('court updated event implements broadcast now and broadcasts on public channel', function () {
    $event = new CourtUpdated(courtId: 4, timerState: ['status' => 'running'], eventType: 'timer');

    expect($event)->toBeInstanceOf(ShouldBroadcastNow::class);

    $channels = $event->broadcastOn();
    expect($channels)->toBeArray()
        ->toHaveCount(1)
        ->and($channels[0])->toBeInstanceOf(Channel::class)
        ->and($channels[0]->name)->toBe('court.4');

    $data = $event->broadcastWith();
    expect($data)->toBeArray()
        ->toHaveKey('court_id', 4)
        ->toHaveKey('timer_state', ['status' => 'running'])
        ->toHaveKey('event_type', 'timer');
});

test('match updated event implements broadcast now and broadcasts on public channel', function () {
    $event = new MatchUpdated(matchId: 12, eventType: 'score');

    expect($event)->toBeInstanceOf(ShouldBroadcastNow::class);

    $channels = $event->broadcastOn();
    expect($channels)->toBeArray()
        ->toHaveCount(1)
        ->and($channels[0])->toBeInstanceOf(Channel::class)
        ->and($channels[0]->name)->toBe('match.12');

    $data = $event->broadcastWith();
    expect($data)->toBeArray()
        ->toHaveKey('match_id', 12)
        ->toHaveKey('event_type', 'score');
});
