<?php

namespace App\Services;

use App\Models\RandoriJudgeScore;
use App\Models\RandoriMatchResult;
use Illuminate\Support\Facades\DB;

class RandoriScoringService
{
    /**
     * Submit or update a judge's score.
     */
    public function submitScore(int $matchId, string $bracketNode, int $judgeIndex, string $actionType, string $color, int $value = 1)
    {
        $column = "{$actionType}_{$color}"; // e.g. waza_ari_aka
        
        $score = RandoriJudgeScore::firstOrCreate(
            [
                'match_number_id' => $matchId,
                'bracket_node' => $bracketNode,
                'judge_index' => $judgeIndex,
            ]
        );
        
        $score->increment($column, $value);
        return $score;
    }

    /**
     * Set a judge's score explicitly instead of incrementing.
     */
    public function setScore(int $matchId, string $bracketNode, int $judgeIndex, string $actionType, string $color, int $value)
    {
        $column = "{$actionType}_{$color}";
        
        $score = RandoriJudgeScore::updateOrCreate(
            [
                'match_number_id' => $matchId,
                'bracket_node' => $bracketNode,
                'judge_index' => $judgeIndex,
            ],
            [
                $column => $value
            ]
        );
        
        return $score;
    }

    /**
     * Calculate aggregated results from all judges.
     */
    public function calculateResult(int $matchId, string $bracketNode)
    {
        $scores = RandoriJudgeScore::where('match_number_id', $matchId)
            ->where('bracket_node', $bracketNode)
            ->get();
            
        // Calculate totals based on Ippon and Waza-ari
        $totalAka = $scores->sum('ippon_aka') * 10 + $scores->sum('waza_ari_aka') * 5 - $scores->sum('hansoku_aka');
        $totalShiro = $scores->sum('ippon_shiro') * 10 + $scores->sum('waza_ari_shiro') * 5 - $scores->sum('hansoku_shiro');
        
        $winnerColor = null;
        if ($totalAka > $totalShiro) {
            $winnerColor = 'athlete1'; // aka
        } elseif ($totalShiro > $totalAka) {
            $winnerColor = 'athlete2'; // shiro
        }
        
        return [
            'total_aka' => $totalAka,
            'total_shiro' => $totalShiro,
            'winnerColor' => $winnerColor,
            'scores' => $scores
        ];
    }
}
