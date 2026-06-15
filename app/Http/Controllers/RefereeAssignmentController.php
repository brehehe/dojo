<?php

namespace App\Http\Controllers;

use App\Events\CourtUpdated;
use App\Http\Requests\ResetActiveRefereesRequest;
use App\Http\Requests\ResetCourtRefereesRequest;
use App\Http\Requests\SaveRefereeAssignmentRequest;
use App\Models\ActiveCourtReferee;
use App\Models\ScheduleReferee;
use App\Services\StateCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RefereeAssignmentController extends Controller
{
    public function __construct(
        protected StateCache $stateCache,
    ) {}

    public function saveRefereeAssignment(SaveRefereeAssignmentRequest $request): JsonResponse
    {
        $courtId = $request->input('court_id');
        $rundownId = $request->input('rundown_id');
        $sessionId = $request->input('session_time_id');
        $selectedReferees = $request->input('referees');

        DB::beginTransaction();
        try {
            ScheduleReferee::where('rundown_id', $rundownId)
                ->where('session_time_id', $sessionId)
                ->where('court_id', $courtId)
                ->where('judge_index', '>', 0)
                ->delete();

            foreach ($selectedReferees as $index => $refereeId) {
                ScheduleReferee::create([
                    'rundown_id' => $rundownId,
                    'session_time_id' => $sessionId,
                    'court_id' => $courtId,
                    'referee_id' => $refereeId,
                    'judge_index' => $index + 1,
                ]);
            }

            ActiveCourtReferee::where('court_id', $courtId)->delete();
            foreach ($selectedReferees as $index => $refereeId) {
                ActiveCourtReferee::create([
                    'court_id' => $courtId,
                    'referee_id' => $refereeId,
                    'judge_index' => $index + 1,
                ]);
            }

            DB::commit();
            $this->stateCache->bumpCourt($courtId);
            event(new CourtUpdated($courtId, null, 'referee_assignment'));

            return response()->json([
                'success' => true,
                'text' => 'Panel wasit telah diperbarui.',
            ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500)->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }
    }

    public function resetActiveReferees(ResetActiveRefereesRequest $request): JsonResponse
    {
        $courtId = $request->input('court_id');
        ActiveCourtReferee::where('court_id', $courtId)->delete();
        $this->stateCache->bumpCourt($courtId);
        event(new CourtUpdated($courtId, null, 'referee_assignment'));

        return response()->json([
            'success' => true,
            'text' => 'Seluruh wasit aktif untuk lapangan ini telah dihapus.',
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function resetCourtReferees(ResetCourtRefereesRequest $request): JsonResponse
    {
        $courtId = $request->input('court_id');
        $rundownId = $request->input('rundown_id');
        $sessionId = $request->input('session_time_id');

        ScheduleReferee::where('court_id', $courtId)
            ->where('rundown_id', $rundownId)
            ->where('session_time_id', $sessionId)
            ->where('judge_index', '>', 0)
            ->delete();

        ActiveCourtReferee::where('court_id', $courtId)->delete();
        $this->stateCache->bumpCourt($courtId);
        event(new CourtUpdated($courtId, null, 'referee_assignment'));

        return response()->json([
            'success' => true,
            'text' => 'Seluruh wasit untuk sesi ini telah dikosongkan.',
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
}
