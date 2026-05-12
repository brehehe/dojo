<?php

namespace App\Traits;

use App\Models\Referee;
use App\Models\RefereeScoreDetail;
use App\Models\Registration;

trait HasRefereeAnalysis
{
    protected function getRefereeAnalysis($filters = [])
    {
        // 1. Get all scores for calculation
        $query = RefereeScoreDetail::where('scorable_type', Registration::class)
            ->where('total_calculated_score', '>', 0)
            ->join('drawing_match_numbers', function ($join) {
                $join->on('referee_score_details.match_number_id', '=', 'drawing_match_numbers.match_number_id')
                    ->on('referee_score_details.scorable_id', '=', 'drawing_match_numbers.registration_id');
            })
            ->join('match_numbers', 'referee_score_details.match_number_id', '=', 'match_numbers.id')
            ->join('courts', 'drawing_match_numbers.court_id', '=', 'courts.id')
            ->select('referee_score_details.*', 'courts.name as court_name');

        // Apply filters
        if (! empty($filters['search'])) {
            $query->whereHas('scorable.athletes', function ($q) use ($filters) {
                $q->where('name', 'ilike', '%'.$filters['search'].'%');
            });
        }

        if (! empty($filters['ageGroupFilter'])) {
            $query->whereHas('matchNumber', function ($q) use ($filters) {
                $q->where('age_group_id', $filters['ageGroupFilter']);
            });
        }

        if (! empty($filters['matchNumberFilter'])) {
            $query->where('referee_score_details.match_number_id', $filters['matchNumberFilter']);
        }

        if (! empty($filters['refereeFilter'])) {
            $query->where('referee_id', $filters['refereeFilter']);
        }

        if (! empty($filters['genderFilter'])) {
            $query->whereHas('matchNumber', function ($q) use ($filters) {
                $q->where('gender', $filters['genderFilter']);
            });
        }

        if (! empty($filters['roundFilter'])) {
            $query->where('drawing_match_numbers.round', $filters['roundFilter']);
        }

        if (! empty($filters['courtFilter'])) {
            $query->where('drawing_match_numbers.court_id', $filters['courtFilter']);
        }

        if (! empty($filters['draftTypeFilter'])) {
            $query->where('match_numbers.draft_type', $filters['draftTypeFilter']);
        }

        if (! empty($filters['contingentId'])) {
            $query->whereHas('scorable', function ($q) use ($filters) {
                $q->where('contingent_id', $filters['contingentId']);
            });
        }

        $details = $query->get();

        // 2. Calculate Reference Scores (Average per Match/Registration)
        $referenceScores = $details->groupBy('scorable_id')->map(function ($group) {
            return $group->avg('total_calculated_score');
        });

        $referees = Referee::with('user')->get();

        $analysis = [];
        foreach ($referees as $rf) {
            $rfDetails = $details->where('referee_id', $rf->id);
            if ($rfDetails->count() === 0) {
                continue;
            }

            $scores = $rfDetails->pluck('total_calculated_score')->values()->toArray();
            $refs = $rfDetails->map(fn ($d) => $referenceScores[$d->scorable_id] ?? 0)->values()->toArray();

            // IAW (Referee Accuracy Index): (Σ Nilai Wasit / Σ Nilai Referensi) × 100%
            $sumWasit = array_sum($scores);
            $sumRef = array_sum($refs);
            $iaw = $sumRef > 0 ? ($sumWasit / $sumRef) * 100 : 0;

            $avgRef = count($refs) > 0 ? array_sum($refs) / count($refs) : 0;

            // IK (Consistency Index): 1 - (σ / μ)
            $mu = count($scores) > 0 ? array_sum($scores) / count($scores) : 0;
            $sigma = $this->calculateStdDev($scores);
            $ik = $mu > 0 ? 1 - ($sigma / $mu) : 0;

            // IV (Validity Index): Pearson Correlation between scores and reference
            $iv = $this->calculatePearson($scores, $refs);

            $ivInterpretation = match (true) {
                $iv >= 0.8 => 'Validitas Sangat Tinggi',
                $iv >= 0.6 => 'Validitas Tinggi',
                $iv >= 0.4 => 'Validitas Cukup',
                default => 'Validitas Rendah',
            };

            $ivCategory = match (true) {
                $iv >= 0.7 => 'Sangat Baik',
                $iv >= 0.5 => 'Baik',
                $iv >= 0.3 => 'Cukup',
                default => 'Perlu Perbaikan',
            };

            // SKW (Referee Competency Score)
            // IAW_norm: Ideally 100%, penalize both over-scoring and under-scoring
            $iawNorm = 100 - abs(100 - $iaw);
            $skw = ($iawNorm * 0.4) + ($ik * 100 * 0.35) + ($iv * 100 * 0.25);

            // Get Primary Court
            $primaryCourt = $rfDetails->first()?->matchNumber?->drawings?->first()?->court?->name ?? 'N/A';

            // Grade Competency
            $grade = match (true) {
                $skw >= 85 => 'A',
                $skw >= 75 => 'B',
                $skw >= 65 => 'C',
                $skw >= 50 => 'D',
                default => 'E',
            };

            $gradeLabel = match ($grade) {
                'A' => 'Sangat Baik',
                'B' => 'Baik',
                'C' => 'Cukup',
                'D' => 'Perlu Bimbingan',
                'E' => 'Kurang',
            };

            $iawCategory = match (true) {
                $iaw >= 98 && $iaw <= 102 => 'Sangat Akurat',
                $iaw >= 95 && $iaw <= 105 => 'Akurat',
                $iaw >= 90 && $iaw <= 110 => 'Cukup Akurat',
                default => 'Kurang Akurat',
            };

            $ikCategory = match (true) {
                $ik >= 0.90 => 'Sangat Konsisten',
                $ik >= 0.80 => 'Konsisten',
                $ik >= 0.70 => 'Cukup Konsisten',
                default => 'Kurang Konsisten',
            };

            $analysis[] = [
                'name' => $rf->name,
                'count' => count($scores),
                'iaw' => round($iaw, 2),
                'iaw_category' => $iawCategory,
                'ik' => round($ik, 3),
                'ik_category' => $ikCategory,
                'iv' => round($iv, 3),
                'iv_interpretation' => $ivInterpretation,
                'iv_category' => $ivCategory,
                'skw' => round($skw, 2),
                'grade' => $grade,
                'grade_label' => $gradeLabel,
                'avg_total' => round($mu, 2),
                'avg_ref' => round($avgRef, 2),
                'min' => ! empty($scores) ? min($scores) : 0,
                'max' => ! empty($scores) ? max($scores) : 0,
                'std_dev' => round($sigma, 3),
                'primary_court' => $rfDetails->first()?->court_name ?? 'N/A',
            ];
        }

        return collect($analysis);
    }

    protected function calculateStdDev(array $numbers): float
    {
        $n = count($numbers);
        if ($n <= 1) {
            return 0;
        }

        $mean = array_sum($numbers) / $n;
        $variance = array_reduce($numbers, function ($carry, $item) use ($mean) {
            return $carry + pow($item - $mean, 2);
        }, 0) / ($n - 1);

        return sqrt($variance);
    }

    protected function calculatePearson(array $x, array $y): float
    {
        $n = count($x);
        if ($n === 0 || count($y) !== $n) {
            return 0;
        }

        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumX2 = array_reduce($x, fn ($carry, $item) => $carry + pow($item, 2), 0);
        $sumY2 = array_reduce($y, fn ($carry, $item) => $carry + pow($item, 2), 0);
        $sumXY = 0;
        for ($i = 0; $i < $n; $i++) {
            $sumXY += $x[$i] * $y[$i];
        }

        $numerator = ($n * $sumXY) - ($sumX * $sumY);
        $denominator = sqrt(max(0, ($n * $sumX2 - pow($sumX, 2)) * ($n * $sumY2 - pow($sumY, 2))));

        if ($denominator == 0) {
            return 0;
        }

        return $numerator / $denominator;
    }
}
