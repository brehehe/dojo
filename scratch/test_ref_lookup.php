<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ScheduleReferee;

$courtId = 1;
$rundownId = 1;
$sessionId = 1;

$refs = ScheduleReferee::where('court_id', $courtId)
    ->where('rundown_id', $rundownId)
    ->where('session_time_id', $sessionId)
    ->where('judge_index', '>', 0)
    ->orderBy('judge_index')
    ->pluck('referee_id')
    ->map(fn($id) => (string)$id)
    ->toArray();

print_r($refs);
