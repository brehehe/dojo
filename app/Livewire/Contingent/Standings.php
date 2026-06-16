<?php

namespace App\Livewire\Contingent;

use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.premium')]
class Standings extends Component
{
    #[Url]
    public string $filterType = 'embu';

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user->contingent()->exists()) {
            redirect()->route('contingent.setup');
        }
    }

    public function render()
    {
        $contingent = Auth::user()->contingent;

        $registrationIds = Registration::where('contingent_id', $contingent->id)
            ->pluck('id')
            ->toArray();

        $standings = collect([]);

        if ($this->filterType === 'embu') {
            $matchNumberIds = MatchNumber::where('draft_type', 'embu')
                ->whereHas('drawings', fn ($q) => $q->whereIn('registration_id', $registrationIds))
                ->pluck('id');

            $scores = EmbuScore::whereIn('match_number_id', $matchNumberIds)
                ->with(['matchNumber.ageGroup', 'registration.contingent', 'drawing', 'registration.athletes'])
                ->get()
                ->filter(function ($score) {
                    if ($score->drawing_id && ! $score->drawing) {
                        return false;
                    }

                    return true;
                });

            $groupedStandings = [];
            foreach ($scores->groupBy('match_number_id') as $matchNumberId => $matchScores) {
                $groupedByDrawing = [];
                $scoresByUnit = $matchScores->groupBy(function ($score) {
                    if ($score->drawing && is_array($score->drawing->metadata) && ! empty($score->drawing->metadata['athlete_ids'])) {
                        $athleteIds = $score->drawing->metadata['athlete_ids'];
                        sort($athleteIds);

                        return $score->registration_id.'_'.implode(',', $athleteIds);
                    }

                    return 'reg_'.$score->registration_id;
                });

                // Pre-calculate team indices for each registration_id
                $sortedUnitsForReg = [];
                foreach ($scoresByUnit as $unitId => $drawingScores) {
                    $penyisihan = $drawingScores->firstWhere('round_label', 'Penyisihan');
                    $final = $drawingScores->firstWhere('round_label', 'Final');
                    $drawing = $final ? $final->drawing : ($penyisihan ? $penyisihan->drawing : null);
                    $regId = $final ? $final->registration_id : ($penyisihan ? $penyisihan->registration_id : null);

                    if ($regId) {
                        $sortedUnitsForReg[$regId][] = [
                            'unit_id' => $unitId,
                            'seq' => $drawing ? ($drawing->sequence_number ?? $drawing->id) : 999999,
                        ];
                    }
                }

                $teamLabels = [];
                foreach ($sortedUnitsForReg as $regId => $units) {
                    if (count($units) > 1) {
                        usort($units, fn ($a, $b) => $a['seq'] <=> $b['seq']);
                        foreach ($units as $index => $u) {
                            $teamLabels[$u['unit_id']] = 'Tim '.($index + 1);
                        }
                    }
                }

                foreach ($scoresByUnit as $unitId => $drawingScores) {
                    $penyisihan = $drawingScores->firstWhere('round_label', 'Penyisihan');
                    $final = $drawingScores->firstWhere('round_label', 'Final');

                    $rank = $final ? $final->rank : ($penyisihan ? $penyisihan->rank : null);
                    $nilaiAkhir = ($penyisihan ? (float) $penyisihan->nilai_akhir : 0.0) + ($final ? (float) $final->nilai_akhir : 0.0);

                    $registration = $final ? $final->registration : ($penyisihan ? $penyisihan->registration : null);
                    $matchNumber = $final ? $final->matchNumber : ($penyisihan ? $penyisihan->matchNumber : null);
                    $drawing = $final ? $final->drawing : ($penyisihan ? $penyisihan->drawing : null);

                    $groupedByDrawing[] = (object) [
                        'unit_id' => $unitId,
                        'registration_id' => $registration ? $registration->id : null,
                        'registration' => $registration,
                        'matchNumber' => $matchNumber,
                        'drawing' => $drawing,
                        'rank' => $rank,
                        'penyisihan_score' => $penyisihan ? $penyisihan->nilai_akhir : null,
                        'final_score' => $final ? $final->nilai_akhir : null,
                        'nilai_akhir' => $nilaiAkhir,
                        'team_label' => $teamLabels[$unitId] ?? null,
                    ];
                }

                usort($groupedByDrawing, function ($a, $b) {
                    $rA = $a->rank;
                    $rB = $b->rank;
                    if ($rA !== null && $rB !== null) {
                        if ($rA !== $rB) {
                            return $rA <=> $rB;
                        }
                    } elseif ($rA !== null) {
                        return -1;
                    } elseif ($rB !== null) {
                        return 1;
                    }

                    return $b->nilai_akhir <=> $a->nilai_akhir;
                });

                $groupedStandings[$matchNumberId] = collect($groupedByDrawing);
            }

            $standings = $groupedStandings;
        } else {
            $standings = MatchNumber::where('draft_type', 'randori')
                ->whereHas('drawings', fn ($q) => $q->whereIn('registration_id', $registrationIds))
                ->with([
                    'ageGroup',
                    'randoriResults' => fn ($q) => $q->with('winner')->orderBy('bracket_node'),
                    'drawings' => fn ($q) => $q->with(['registration.contingent', 'pool']),
                ])
                ->get();
        }

        return view('livewire.contingent.standings', [
            'contingent' => $contingent,
            'standings' => $standings,
            'registrationIds' => $registrationIds,
        ]);
    }
}
