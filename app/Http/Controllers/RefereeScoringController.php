<?php

namespace App\Http\Controllers;

use App\Http\Requests\Referee\SaveRefereeScoreRequest;
use App\Http\Requests\Referee\SubmitRefereeScoreRequest;
use App\Services\RefereeScoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class RefereeScoringController extends Controller
{
    public function __construct(
        protected RefereeScoringService $scoringService
    ) {}

    /**
     * Renders the immersive referee scoring dashboard (Svelte).
     */
    public function index(): Response
    {
        return Inertia::render('RefereeScoring');
    }

    /**
     * Fetch the current active match and scoring state for the referee.
     */
    public function state(): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $state = $this->scoringService->getRefereeScoringState($user);

        return response()->json($state);
    }

    /**
     * Handle real-time auto-saving of scoring details.
     */
    public function save(SaveRefereeScoreRequest $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $result = $this->scoringService->saveScore(
            $user,
            $request->input('embuItems', []),
            (string) $request->input('notes', '')
        );

        if (! $result['success']) {
            return response()->json(['success' => false, 'message' => $result['message'] ?? 'Save failed'], 400);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Submit scores and signature.
     */
    public function submit(SubmitRefereeScoreRequest $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $result = $this->scoringService->submitScore(
            $user,
            (string) $request->input('signature'),
            $request->input('embuItems', []),
            (string) $request->input('notes', '')
        );

        if (! $result['success']) {
            return response()->json(['success' => false, 'message' => $result['message'] ?? 'Submit failed'], 400);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'] ?? 'Nilai telah disimpan.',
        ]);
    }
}
