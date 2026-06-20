<?php

namespace App\Services;

use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;

class BracketService
{
    public function recalculateRanks(array $matchNumberIds, string $currentRound): void
    {
        $scores = EmbuScore::whereIn('match_number_id', $matchNumberIds)
            ->where('round_label', $currentRound)
            ->get();

        if ($currentRound === 'Penyisihan') {
            $sorted = $scores->sort(function ($a, $b) {
                if ($a->nilai_akhir != $b->nilai_akhir) {
                    return $b->nilai_akhir <=> $a->nilai_akhir;
                }

                return $b->judge_1 <=> $a->judge_1;
            })->values();
        } else {
            $penyisihanScores = EmbuScore::whereIn('match_number_id', $matchNumberIds)
                ->where('round_label', 'Penyisihan')
                ->get()
                ->groupBy('registration_id')
                ->map(fn ($group) => $group->sortByDesc('tiebreak_round')->first());

            $sorted = $scores->sort(function ($a, $b) use ($penyisihanScores) {
                $pA = $penyisihanScores[$a->registration_id] ?? null;
                $pB = $penyisihanScores[$b->registration_id] ?? null;

                $totalA = $a->nilai_akhir + ($pA ? $pA->nilai_akhir : 0);
                $totalB = $b->nilai_akhir + ($pB ? $pB->nilai_akhir : 0);

                if ($totalA != $totalB) {
                    return $totalB <=> $totalA;
                }

                return $b->judge_1 <=> $a->judge_1;
            })->values();
        }

        foreach ($sorted as $index => $score) {
            $score->update(['rank' => $index + 1]);
        }
    }

    public function detectTies(array $matchNumberIds, string $currentRound): array
    {
        if ($currentRound !== 'Penyisihan') {
            return [];
        }

        $scores = EmbuScore::whereIn('match_number_id', $matchNumberIds)
            ->where('round_label', 'Penyisihan')
            ->get();

        $sorted = $scores->sort(function ($a, $b) {
            if ($a->nilai_akhir != $b->nilai_akhir) {
                return $b->nilai_akhir <=> $a->nilai_akhir;
            }

            return $b->judge_1 <=> $a->judge_1;
        })->values();

        $firstMatch = MatchNumber::find($matchNumberIds[0] ?? null);
        $drawing = $firstMatch ? $firstMatch->drawing_data : null;
        $threshold = 4;
        if ($drawing && isset($drawing['qualifiers'])) {
            $threshold = (int) $drawing['qualifiers'];
        }

        if ($sorted->count() <= $threshold) {
            return [];
        }

        $boundaryScore = $sorted->get($threshold - 1);
        if (! $boundaryScore) {
            return [];
        }

        $tied = $sorted->filter(
            fn ($s) => (float) $s->nilai_akhir === (float) $boundaryScore->nilai_akhir &&
            (float) $s->judge_1 === (float) $boundaryScore->judge_1
        );

        return $tied->count() > 1 ? $tied->pluck('registration_id')->toArray() : [];
    }

    public function migrateLegacyBracket(array $data): array
    {
        if (empty($data) || (! isset($data['rounds']) && ! isset($data['bracket']))) {
            return $data;
        }

        $rounds = $data['rounds'] ?? [];
        $bracketSize = $data['bracket_size'] ?? 0;

        if (empty($rounds) || $bracketSize < 2) {
            return $data;
        }

        $ubRounds = [];
        foreach ($rounds as $r => $matches) {
            $roundArr = [];
            foreach ($matches as $m => $match) {
                $roundArr[] = array_merge($match, [
                    'winner' => null,
                    'winner_data' => null,
                    'winner_next' => null,
                    'loser_next' => null,
                ]);
            }
            $ubRounds[] = $roundArr;
        }

        return [
            'bracket_type' => 'double_elimination',
            'bracket_size' => $bracketSize,
            'total_athletes' => $data['total_entries'] ?? 0,
            'upper_bracket' => ['rounds' => $ubRounds],
            'lower_bracket' => ['rounds' => []],
            'grand_final' => ['athlete1' => null, 'athlete2' => null, 'winner' => null, 'winner_data' => null],
            'juara' => [],
        ];
    }

    public function placeAthlete(array $data, array $next, array $athleteData, array $matchNumberIds): array
    {
        $b = $next['bracket'];
        $slot = $next['slot'] ?? 'athlete1';
        if ($b === 'ub') {
            $data['upper_bracket']['rounds'][$next['round']][$next['match']][$slot] = $athleteData;
        } elseif ($b === 'lb') {
            $data['lower_bracket']['rounds'][$next['round']][$next['match']][$slot] = $athleteData;
        } elseif ($b === 'gf') {
            $data['grand_final'][$slot] = $athleteData;
        }

        $round = $next['round'] ?? 0;
        $matchVal = $next['match'] ?? 0;
        $nodeKey = $b.'_'.$round.'_'.$matchVal;
        $side = $slot === 'athlete1' ? 'RED' : 'BLUE';

        $drawings = DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)->get();

        foreach ($drawings as $d) {
            $meta = $d->metadata;
            if (is_string($meta)) {
                $meta = json_decode($meta, true);
            }

            if (($meta['node_key'] ?? null) === $nodeKey && ($meta['side'] ?? null) === $side) {
                $d->registration_id = $athleteData['registration_id'] ?? null;
                $meta['athlete_id'] = $athleteData['id'] ?? null;
                $meta['athlete_name'] = $athleteData['name'] ?? 'TBD';
                $meta['contingent'] = $athleteData['contingent'] ?? 'TBD';
                $d->metadata = $meta;
                $d->save();
            }
        }

        return $data;
    }

    public function propagateBracketByes(array $data, array $matchNumberIds): array
    {
        $changed = true;
        $maxIterations = 10;
        $iteration = 0;

        while ($changed && $iteration < $maxIterations) {
            $changed = false;
            $iteration++;

            if (isset($data['upper_bracket']['rounds'])) {
                foreach ($data['upper_bracket']['rounds'] as $rIdx => $round) {
                    foreach ($round as $mIdx => $match) {
                        if (($match['winner'] ?? null) === null) {
                            $a1Bye = ($match['athlete1']['id'] ?? '') === 'BYE';
                            $a2Bye = ($match['athlete2']['id'] ?? '') === 'BYE';

                            if ($a1Bye || $a2Bye) {
                                $winnerSlot = $a1Bye ? 'athlete2' : 'athlete1';
                                $data['upper_bracket']['rounds'][$rIdx][$mIdx]['winner'] = $winnerSlot;
                                $data['upper_bracket']['rounds'][$rIdx][$mIdx]['winner_data'] = $match[$winnerSlot] ?? null;
                                $data['upper_bracket']['rounds'][$rIdx][$mIdx]['is_bye'] = true;
                                $changed = true;
                                $match = $data['upper_bracket']['rounds'][$rIdx][$mIdx];
                            }
                        }

                        if (($match['winner'] ?? null) !== null && ($match['winner_next'] ?? null)) {
                            $winnerData = $match['winner_data'] ?? $match[$match['winner']] ?? null;
                            if ($winnerData) {
                                $next = $match['winner_next'];
                                $target = $this->getAthleteInSlot($data, $next);
                                if (! $target || $target['id'] !== $winnerData['id']) {
                                    $data = $this->placeAthlete($data, $next, $winnerData, $matchNumberIds);
                                    $changed = true;
                                    $match = $data['upper_bracket']['rounds'][$rIdx][$mIdx];
                                }
                            }
                        }
                    }
                }
            }

            if (isset($data['lower_bracket']['rounds'])) {
                foreach ($data['lower_bracket']['rounds'] as $rIdx => $round) {
                    foreach ($round as $mIdx => $match) {
                        if (($match['winner'] ?? null) === null) {
                            $a1Bye = ($match['athlete1']['id'] ?? '') === 'BYE';
                            $a2Bye = ($match['athlete2']['id'] ?? '') === 'BYE';

                            if ($a1Bye || $a2Bye) {
                                $winnerSlot = $a1Bye ? 'athlete2' : 'athlete1';
                                $data['lower_bracket']['rounds'][$rIdx][$mIdx]['winner'] = $winnerSlot;
                                $data['lower_bracket']['rounds'][$rIdx][$mIdx]['winner_data'] = $match[$winnerSlot] ?? null;
                                $data['lower_bracket']['rounds'][$rIdx][$mIdx]['is_bye'] = true;
                                $changed = true;
                                $match = $data['lower_bracket']['rounds'][$rIdx][$mIdx];
                            }
                        }

                        if (($match['winner'] ?? null) !== null && ($match['winner_next'] ?? null)) {
                            $winnerData = $match['winner_data'] ?? $match[$match['winner']] ?? null;
                            if ($winnerData) {
                                $next = $match['winner_next'];
                                $target = $this->getAthleteInSlot($data, $next);
                                if (! $target || $target['id'] !== $winnerData['id']) {
                                    $data = $this->placeAthlete($data, $next, $winnerData, $matchNumberIds);
                                    $changed = true;
                                    $match = $data['lower_bracket']['rounds'][$rIdx][$mIdx];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function getAthleteInSlot(array $data, array $next): ?array
    {
        $b = $next['bracket'];
        $slot = $next['slot'] ?? 'athlete1';
        if ($b === 'ub') {
            return $data['upper_bracket']['rounds'][$next['round']][$next['match']][$slot] ?? null;
        }
        if ($b === 'lb') {
            return $data['lower_bracket']['rounds'][$next['round']][$next['match']][$slot] ?? null;
        }
        if ($b === 'gf') {
            return $data['grand_final'][$slot] ?? null;
        }

        return null;
    }
}
