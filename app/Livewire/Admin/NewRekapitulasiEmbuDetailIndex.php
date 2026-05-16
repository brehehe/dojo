<?php

namespace App\Livewire\Admin;

use App\Exports\RekapitulasiEmbuExport;
use App\Models\Athlete;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;
use App\Models\MatchNumberMerge;
use App\Models\ScheduleReferee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.premium')]
class NewRekapitulasiEmbuDetailIndex extends Component
{
    #[Url(as: 'round')]
    public ?string $urlRound = null;

    #[Url(as: 'pool_id')]
    public ?int $urlPoolId = null;

    public MatchNumber $matchNumber;

    public $merge = null;

    public $displayName = '';

    public ?int $selectedPoolId = null;

    public $currentRound = 'Penyisihan';

    public $matchNumberIds = [];

    public function mount(MatchNumber $matchNumber)
    {
        $this->matchNumber = $matchNumber;

        // Check if this match is part of a merge
        $mergeDetails = DB::table('match_number_merge_details')
            ->where('match_number_id', $matchNumber->id)
            ->first();

        if ($mergeDetails) {
            $this->merge = MatchNumberMerge::find($mergeDetails->match_number_merge_id);
            $this->matchNumberIds = DB::table('match_number_merge_details')
                ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                ->pluck('match_number_id')
                ->toArray();
        } else {
            $this->matchNumberIds = [$matchNumber->id];
        }

        $this->matchNumber->load([
            'athletes',
            'embuScores',
            'drawings.court',
            'drawings.sessionTime',
            'drawings.rundown',
            'drawings.pool',
            'ageGroup',
        ]);

        if ($this->urlRound) {
            $this->currentRound = $this->urlRound;
        } else {
            $hasFinalists = $this->matchNumber->embuScores
                ->where('round_label', 'Final')
                ->count() > 0;
            $this->currentRound = $hasFinalists ? 'Final' : 'Penyisihan';
        }

        if ($this->urlPoolId) {
            $this->selectedPoolId = $this->urlPoolId;
        } else {
            $firstDrawing = $this->matchNumber->drawings->where('round', $this->currentRound)->whereNotNull('pool_id')->first();
            if ($firstDrawing) {
                $this->selectedPoolId = $firstDrawing->pool_id;
            }
        }
    }

    public function setPool(?int $poolId)
    {
        $this->selectedPoolId = $poolId;
        $this->urlPoolId = $poolId;
    }

    public function exportToExcel()
    {
        $registrations = $this->getRegistrationsData();

        $firstDrawingQuery = $this->matchNumber->drawings->where('round', $this->currentRound);
        if ($this->currentRound === 'Penyisihan' && $this->selectedPoolId) {
            $firstDrawingQuery = $firstDrawingQuery->where('pool_id', $this->selectedPoolId);
        }
        $firstDrawing = $firstDrawingQuery->first();

        $sessionDate = $firstDrawing?->sessionTime?->date ?? now();
        $courtOrder = $firstDrawing?->court?->order ?? '-';
        $poolName = $firstDrawing?->pool?->name ?? ($firstDrawing?->metadata['pool'] ?? '-');

        $metadata = [
            'display_name' => $this->displayName,
            'current_round' => $this->currentRound,
            'day' => Carbon::parse($sessionDate)->translatedFormat('l'),
            'date' => Carbon::parse($sessionDate)->translatedFormat('d F Y'),
            'age_group' => $this->matchNumber->ageGroup?->name ?? '-',
            'pool' => $poolName,
            'court' => 'C'.$courtOrder,
            'koordinator' => $firstDrawing?->metadata['officials']['koordinator_lapangan'] ?? 'Drs. H. Bambang Supriyanto, M.Pd.',
            'paniteras' => $firstDrawing?->metadata['officials']['panitera'] ?? ['Rini Astuti, S.Or.', 'Ahmad Fauzi', 'Dewi Lestari'],
        ];

        return Excel::download(
            new RekapitulasiEmbuExport($registrations, $metadata),
            'rekapitulasi_embu_'.$this->matchNumber->id.'.xlsx'
        );
    }

    protected function getRegistrationsData()
    {
        $drawingsQuery = DrawingMatchNumber::with(['registration.contingent'])
            ->whereIn('match_number_id', $this->matchNumberIds)
            ->where('round', $this->currentRound);

        if ($this->currentRound === 'Penyisihan' && $this->selectedPoolId) {
            $drawingsQuery->where('pool_id', $this->selectedPoolId);
        }

        $drawingsList = $drawingsQuery->orderBy('sequence_number')->get();

        $allScores = EmbuScore::whereIn('match_number_id', $this->matchNumberIds)
            ->where('round_label', $this->currentRound)
            ->get();

        $registrations = $drawingsList->map(function ($drawing) use ($allScores) {
            $regId = $drawing->registration_id;
            $matchId = $drawing->match_number_id;
            $registration = $drawing->registration;

            $metaAthleteIds = $drawing->metadata['athlete_ids'] ?? [];

            if (! empty($metaAthleteIds)) {
                $athletes = Athlete::whereIn('id', $metaAthleteIds)->get();
            } else {
                $athletes = Athlete::whereHas('matchNumbers', function ($q) use ($matchId, $regId) {
                    $q->where('match_numbers.id', $matchId)
                        ->where('athlete_match_number.registration_id', $regId);
                })->get();
            }

            $score = $allScores->where('registration_id', $regId)
                ->where('match_number_id', $matchId)
                ->where('drawing_id', $drawing->id)
                ->sortByDesc('tiebreak_round')
                ->first();

            if (! $score) {
                $score = $allScores->where('registration_id', $regId)
                    ->where('match_number_id', $matchId)
                    ->whereNull('drawing_id')
                    ->sortByDesc('tiebreak_round')
                    ->first();
            }

            $scoreHistory = $allScores->where('registration_id', $regId)
                ->where('match_number_id', $matchId)
                ->where('drawing_id', $drawing->id)
                ->sortBy('tiebreak_round')
                ->values();

            $accumulatedScore = 0;
            $penyisihanScore = null;

            if ($this->currentRound === 'Final') {
                $penyisihanScore = EmbuScore::whereIn('match_number_id', $this->matchNumberIds)
                    ->where('registration_id', $regId)
                    ->where('round_label', 'Penyisihan')
                    ->orderByDesc('tiebreak_round')
                    ->first();

                if ($penyisihanScore) {
                    $accumulatedScore += $penyisihanScore->nilai_akhir;
                }
            }

            if ($score) {
                $accumulatedScore += $score->nilai_akhir;
            }

            // Calculate Nilai Awal and Nilai Akhir for display/export
            $rawVals = $score ? [$score->judge_1, $score->judge_2, $score->judge_3, $score->judge_4, $score->judge_5] : [0, 0, 0, 0, 0];
            $scoredCount = count(array_filter($rawVals, fn ($v) => $v > 0));

            if ($scoredCount === 5) {
                $sortedVals = $rawVals;
                asort($sortedVals);
                $vals = array_values($sortedVals);
                $nilaiAwal = $vals[1] + $vals[2] + $vals[3];
            } else {
                $nilaiAwal = array_sum($rawVals);
            }

            $nilaiAwal = $score?->total_score > 0 ? $score->total_score : $nilaiAwal;
            $nilaiAkhir = $score?->nilai_akhir > 0 ? $score->nilai_akhir : max(0, $nilaiAwal - ($score?->denda ?? 0));

            return [
                'id' => $regId,
                'drawing_id' => $drawing->id,
                'match_number_id' => $matchId,
                'match_name' => $drawing->matchNumber?->name ?? '—',
                'is_group' => $registration?->is_group,
                'athletes' => $athletes->unique('id'),
                'contingent' => $registration?->contingent,
                'score' => $score,
                'score_history' => $scoreHistory,
                'penyisihan_score' => $penyisihanScore,
                'accumulated_score' => $accumulatedScore,
                'sequence_number' => $drawing->sequence_number ?? 999,
                'nilai_awal' => $nilaiAwal,
                'nilai_akhir' => $nilaiAkhir,
            ];
        });

        return $registrations->sort(function ($a, $b) {
            $rankA = $a['score']?->rank ?? 999;
            $rankB = $b['score']?->rank ?? 999;

            if ($rankA != $rankB) {
                return $rankA <=> $rankB;
            }

            return $a['sequence_number'] <=> $b['sequence_number'];
        })->values();
    }

    public function render()
    {
        $registrations = $this->getRegistrationsData();

        $firstDrawingQuery = $this->matchNumber->drawings->where('round', $this->currentRound);
        if ($this->currentRound === 'Penyisihan' && $this->selectedPoolId) {
            $firstDrawingQuery = $firstDrawingQuery->where('pool_id', $this->selectedPoolId);
        }
        $firstDrawing = $firstDrawingQuery->first();

        $availablePools = collect();
        if ($this->currentRound === 'Penyisihan') {
            $availablePools = $this->matchNumber->drawings
                ->where('round', 'Penyisihan')
                ->whereNotNull('pool_id')
                ->pluck('pool')
                ->unique('id')
                ->values();
        }

        if ($this->merge) {
            $mergedNames = MatchNumber::whereIn('id', $this->matchNumberIds)->pluck('name')->join(', ');
            $this->displayName = ($this->merge->name ?: 'Merged Group').' ('.$mergedNames.')';
        } else {
            $this->displayName = $this->matchNumber->name;
        }

        // Fetch referees for wasit status
        $referees = collect();
        if ($firstDrawing) {
            $referees = ScheduleReferee::with('referee.user')
                ->where('court_id', $firstDrawing->court_id)
                ->where('session_time_id', $firstDrawing->session_time_id)
                ->orderBy('judge_index')
                ->get();
        }

        $officials = $firstDrawing?->metadata['officials'] ?? [];
        $koordinator = $officials['koordinator_lapangan'] ?? 'Drs. H. Bambang Supriyanto, M.Pd.';
        $paniteras = $officials['panitera'] ?? ['Rini Astuti, S.Or.', 'Ahmad Fauzi', 'Dewi Lestari'];

        return view('livewire.admin.new-rekapitulasi-embu-detail-index', [
            'registrations' => $registrations,
            'firstDrawing' => $firstDrawing,
            'availablePools' => $availablePools,
            'referees' => $referees,
            'koordinator' => $koordinator,
            'paniteras' => $paniteras,
        ]);
    }
}
