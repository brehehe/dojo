<?php

namespace App\Livewire\Admin;

use App\Models\Athlete;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;
use App\Models\MatchNumberMerge;
use App\Models\RandoriMatchResult;
use App\Models\RefereeScoreDetail;
use App\Models\ScheduleReferee;
use App\Models\TournamentResult;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.print')]
class NewLaporanRekapPenilaianDetail extends Component
{
    public MatchNumber $matchNumber;

    public string $currentRound = 'Penyisihan';

    public array $matchNumberIds = [];

    public ?MatchNumberMerge $merge = null;

    public string $displayName = '';

    public string $printMode = 'rekap'; // 'rekap', 'juri', 'atlet' for Embu; 'rekap', 'per-match' for Randori

    public string $selectedJuri = '1'; // '1', '2', '3', '4', '5', or 'all'

    public string $selectedAthleteReg = 'all'; // registration_id or 'all'

    public string $selectedMatch = 'all'; // bracket_node (e.g. 'ub_0_0') or 'all'

    public function mount(MatchNumber $matchNumber, string $round = 'Penyisihan'): void
    {
        $this->matchNumber = $matchNumber;
        $this->currentRound = $round;

        // Check if part of a merge
        $mergeDetail = DB::table('match_number_merge_details')
            ->where('match_number_id', $matchNumber->id)
            ->first();

        if ($mergeDetail) {
            $this->merge = MatchNumberMerge::find($mergeDetail->match_number_merge_id);
            $this->matchNumberIds = DB::table('match_number_merge_details')
                ->where('match_number_merge_id', $mergeDetail->match_number_merge_id)
                ->pluck('match_number_id')
                ->toArray();
        } else {
            $this->matchNumberIds = [$matchNumber->id];
        }

        // Build display name
        if ($this->merge) {
            $mergedNames = MatchNumber::whereIn('id', $this->matchNumberIds)->pluck('name')->join(', ');
            $this->displayName = ($this->merge->name ?: 'Merged Group').' ('.$mergedNames.')';
        } else {
            $this->displayName = $matchNumber->name;
        }
    }

    /** @return array<string, mixed> */
    protected function getEmbuData(): array
    {
        $rounds = ['Penyisihan', 'Final'];
        $roundsData = [];

        foreach ($rounds as $round) {
            $drawings = DrawingMatchNumber::with(['registration.contingent', 'court', 'sessionTime', 'pool'])
                ->whereIn('match_number_id', $this->matchNumberIds)
                ->where('round', $round)
                ->orderBy('sequence_number')
                ->get();

            if ($drawings->isEmpty()) {
                continue;
            }

            $allScores = EmbuScore::whereIn('match_number_id', $this->matchNumberIds)
                ->where('round_label', $round)
                ->get();

            $registrations = $drawings->map(function ($drawing) use ($allScores, $round) {
                $regId = $drawing->registration_id;
                $matchId = $drawing->match_number_id;

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

                // Calculate Nilai Awal
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

                // Penyisihan score for final round
                $penyisihanScore = null;
                if ($round === 'Final') {
                    $penyisihanScore = EmbuScore::whereIn('match_number_id', $this->matchNumberIds)
                        ->where('registration_id', $regId)
                        ->where('round_label', 'Penyisihan')
                        ->orderByDesc('tiebreak_round')
                        ->first();
                }

                return [
                    'sequence' => $drawing->sequence_number,
                    'registration_id' => $regId,
                    'athletes' => $athletes->unique('id'),
                    'contingent' => $drawing->registration?->contingent,
                    'score' => $score,
                    'nilai_awal' => $nilaiAwal,
                    'nilai_akhir' => $nilaiAkhir,
                    'denda' => $score?->denda ?? 0,
                    'rank' => $score?->rank ?? '-',
                    'penyisihan_score' => $penyisihanScore,
                    'accumulated' => ($penyisihanScore?->nilai_akhir ?? 0) + $nilaiAkhir,
                ];
            });

            // Sort by rank then sequence
            $registrations = $registrations->sort(function ($a, $b) {
                $rankA = is_numeric($a['rank']) ? (int) $a['rank'] : 999;
                $rankB = is_numeric($b['rank']) ? (int) $b['rank'] : 999;
                if ($rankA !== $rankB) {
                    return $rankA <=> $rankB;
                }

                return $a['sequence'] <=> $b['sequence'];
            })->values();

            $roundsData[$round] = [
                'registrations' => $registrations,
                'drawing' => $drawings->first(),
            ];
        }

        // Champions
        $champions = TournamentResult::whereIn('match_number_id', $this->matchNumberIds)
            ->where('draft_type', 'embu')
            ->with('registration.contingent')
            ->orderBy('rank')
            ->get();

        return [
            'rounds' => $roundsData,
            'champions' => $champions,
        ];
    }

    /** @return array<string, mixed> */
    protected function getRandoriData(): array
    {
        $drawingData = $this->matchNumber->drawing_data ?? [];
        $results = RandoriMatchResult::whereIn('match_number_id', $this->matchNumberIds)
            ->orderBy('bracket_node_index')
            ->get();

        // Organize results by bracket section
        $sections = [];
        foreach ($results as $result) {
            $section = $result->bracket_section ?? 'Utama';
            if (! isset($sections[$section])) {
                $sections[$section] = [];
            }
            $sections[$section][] = $result;
        }

        $champions = TournamentResult::whereIn('match_number_id', $this->matchNumberIds)
            ->where('draft_type', 'randori')
            ->with('registration.contingent')
            ->orderBy('rank')
            ->get();

        return [
            'drawing_data' => $drawingData,
            'results' => $results,
            'sections' => $sections,
            'champions' => $champions,
        ];
    }

    /** @return array<string, mixed> */
    protected function getOfficialData(): array
    {
        // Get first drawing for this match to extract officials metadata
        $drawing = DrawingMatchNumber::whereIn('match_number_id', $this->matchNumberIds)
            ->whereNotNull('court_id')
            ->first();

        $officials = $drawing?->metadata['officials'] ?? [];
        $koordinator = $officials['koordinator_lapangan'] ?? '';
        $paniteras = $officials['panitera'] ?? [];
        $arbitrase = $officials['arbitrase'] ?? '';

        // Get referees from schedule
        $referees = collect();
        if ($drawing) {
            $referees = ScheduleReferee::with('referee.user')
                ->where('court_id', $drawing->court_id)
                ->where('session_time_id', $drawing->session_time_id)
                ->orderBy('judge_index')
                ->get();
        }

        $sessionDate = $drawing?->sessionTime?->date ?? now();
        $courtOrder = $drawing?->court?->order ?? '-';

        return [
            'koordinator' => $koordinator,
            'paniteras' => $paniteras,
            'arbitrase' => $arbitrase,
            'referees' => $referees,
            'court' => 'C'.$courtOrder,
            'day' => Carbon::parse($sessionDate)->translatedFormat('l'),
            'date' => Carbon::parse($sessionDate)->translatedFormat('d F Y'),
            'drawing' => $drawing,
        ];
    }

    public function render()
    {
        $officialData = $this->getOfficialData();

        if ($this->matchNumber->draft_type === 'embu') {
            $data = $this->getEmbuData();
        } else {
            $data = $this->getRandoriData();
        }

        $refereeScoreDetails = RefereeScoreDetail::with('referee.user')
            ->whereIn('match_number_id', $this->matchNumberIds)
            ->get();

        return view('livewire.admin.new-laporan-rekap-penilaian-detail', array_merge($data, $officialData, [
            'matchNumber' => $this->matchNumber,
            'displayName' => $this->displayName,
            'refereeScoreDetails' => $refereeScoreDetails,
        ]))->title('Rekap Penilaian — '.$this->displayName);
    }
}
