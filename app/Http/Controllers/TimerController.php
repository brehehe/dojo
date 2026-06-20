<?php

namespace App\Http\Controllers;

use App\Events\CourtUpdated;
use App\Http\Requests\TimerControlRequest;
use App\Services\StateCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class TimerController extends Controller
{
    public function __construct(
        protected StateCache $stateCache,
    ) {}

    public function timerControl(TimerControlRequest $request): JsonResponse
    {
        $courtId = $request->input('court_id');
        $action = $request->input('action');

        $state = Cache::get("court_{$courtId}_timer", ['status' => 'stopped', 'elapsed_ms' => 0, 'started_at_ms' => null]);

        if ($action === 'countdown') {
            $state['status'] = 'countdown';
            $state['countdown_end_ms'] = floor(microtime(true) * 1000) + 5000;
        } elseif ($action === 'start') {
            if ($state['status'] !== 'running') {
                $state['status'] = 'running';
                $state['started_at_ms'] = floor(microtime(true) * 1000);
            }
        } elseif ($action === 'pause') {
            if ($state['status'] === 'running') {
                $now = floor(microtime(true) * 1000);
                $elapsedSinceStart = $now - $state['started_at_ms'];
                $state['status'] = 'paused';
                $state['elapsed_ms'] += $elapsedSinceStart;
                $state['started_at_ms'] = null;
            }
        } elseif ($action === 'stop') {
            $state = [
                'status' => 'stopped',
                'elapsed_ms' => 0,
                'started_at_ms' => null,
            ];
        }

        Cache::put("court_{$courtId}_timer", $state);
        $this->stateCache->bumpCourt($courtId, bumpDashboard: false);
        $state['server_time_ms'] = floor(microtime(true) * 1000);

        event(new CourtUpdated($courtId, $state, 'timer'));

        return response()->json([
            'success' => true,
            'timer_state' => $state,
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
}
