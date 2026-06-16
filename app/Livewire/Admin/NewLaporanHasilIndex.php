<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Arbitrase\Laporan\AdminLaporanHasilIndex;
use App\Models\Athlete;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\TournamentResult;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.premium')]
class NewLaporanHasilIndex extends AdminLaporanHasilIndex
{
    public bool $showRandoriModal = false;

    public ?int $randoriMatchId = null;

    public string $randoriMatchName = '';

    public array $randoriDrawingData = [];

    public array $randoriAthletes = [];

    public ?int $juara1_id = null;

    public ?int $juara2_id = null;

    public ?int $juara3_id = null;

    public ?int $juara3_bersama_id = null;

    public function getJuaraForMatch(MatchNumber $matchNumber): array
    {
        $matchIds = [$matchNumber->id];
        $mergeDetails = DB::table('match_number_merge_details')
            ->where('match_number_id', $matchNumber->id)
            ->first();

        if ($mergeDetails) {
            $matchIds = DB::table('match_number_merge_details')
                ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                ->pluck('match_number_id')
                ->toArray();
        }

        // 1. Check for saved tournament results first
        $saved = TournamentResult::whereIn('match_number_id', $matchIds)
            ->orderBy('rank')
            ->get();

        if ($saved->isNotEmpty()) {
            $juara = [];
            foreach ($saved as $res) {
                $juara[$res->rank] = [
                    'registration_id' => $res->registration_id,
                    'athlete_names' => $res->athlete_names,
                    'contingent_name' => $res->contingent_name,
                    'penyisihan_score' => (float) $res->penyisihan_score,
                    'final_score' => (float) $res->final_score,
                    'accumulated_score' => (float) $res->accumulated_score,
                ];
            }
        } else {
            // 2. Fallback to parent logic
            $juara = parent::getJuaraForMatch($matchNumber);
        }

        // Map rank 4 to rank 3 if there are exactly 3 participants for Randori
        if (strtolower($matchNumber->draft_type) === 'randori') {
            $participantCount = $matchNumber->athletes()->count();
            if ($participantCount === 3) {
                if (isset($juara[4]) && ! isset($juara[3])) {
                    $juara[3] = $juara[4];
                    unset($juara[4]);
                }
            }
        }

        return $juara;
    }

    protected function computeRandoriJuara(MatchNumber $matchNumber): array
    {
        $data = $matchNumber->drawing_data ?? [];
        $juaraRaw = $data['juara'] ?? [];

        // Collect all real athletes (non-BYE) scanning ALL bracket rounds to catch everyone
        $allAthletes = [];
        $bracketSources = [
            $data['upper_bracket']['rounds'] ?? [],
            $data['lower_bracket']['rounds'] ?? [],
        ];
        foreach ($bracketSources as $rounds) {
            foreach ($rounds as $round) {
                foreach ($round as $m) {
                    foreach (['athlete1', 'athlete2'] as $slot) {
                        $a = $m[$slot] ?? null;
                        if ($a && isset($a['id']) && $a['id'] !== 'BYE') {
                            $allAthletes[(string) $a['id']] = $a;
                        }
                    }
                }
            }
        }
        if (isset($data['grand_final'])) {
            foreach (['athlete1', 'athlete2'] as $slot) {
                $a = $data['grand_final'][$slot] ?? null;
                if ($a && isset($a['id']) && $a['id'] !== 'BYE') {
                    $allAthletes[(string) $a['id']] = $a;
                }
            }
        }

        $participantCount = count($allAthletes);

        $juara = [];
        foreach ($juaraRaw as $rank => $j) {
            if (! $j || empty($j['name'])) {
                continue;
            }

            $savedRank = (int) $rank;
            if ((float) $rank == 3.1 || (float) $rank == 4.0) {
                $savedRank = 4;
            }

            if ($participantCount === 3 && ($savedRank === 3 || $savedRank === 4)) {
                $savedRank = 4;
            }

            $juara[$savedRank] = [
                'registration_id' => $j['registration_id'] ?? null,
                'athlete_names' => $j['name'],
                'contingent_name' => $j['contingent'] ?? '-',
                'penyisihan_score' => 0,
                'final_score' => 0,
                'accumulated_score' => 0,
            ];
        }

        return $juara;
    }

    public function openRandoriModal(int $matchId, string $matchName): void
    {
        $match = MatchNumber::find($matchId);
        if (! $match) {
            return;
        }

        $this->randoriMatchId = $matchId;
        $this->randoriMatchName = $matchName;
        $this->randoriDrawingData = $match->drawing_data ?? [];

        $matchIds = [$match->id];
        $mergeDetails = DB::table('match_number_merge_details')
            ->where('match_number_id', $match->id)
            ->first();

        if ($mergeDetails) {
            $matchIds = DB::table('match_number_merge_details')
                ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                ->pluck('match_number_id')
                ->toArray();
        }

        // Load unique athletes
        $this->randoriAthletes = Athlete::whereHas('matchNumbers', function ($query) use ($matchIds) {
            $query->whereIn('match_numbers.id', $matchIds);
        })
            ->get()
            ->map(function ($athlete) use ($matchIds) {
                $contingentName = '-';
                $pivot = DB::table('athlete_match_number')
                    ->where('athlete_id', $athlete->id)
                    ->whereIn('match_number_id', $matchIds)
                    ->first();
                if ($pivot && $pivot->registration_id) {
                    $reg = Registration::with('contingent')->find($pivot->registration_id);
                    $contingentName = $reg?->contingent?->name ?? '-';
                }

                return [
                    'id' => $athlete->id,
                    'name' => $athlete->name,
                    'contingent' => $contingentName,
                    'registration_id' => $pivot?->registration_id ?? null,
                ];
            })
            ->sortBy('name')
            ->values()
            ->toArray();

        // Load existing saved results if any
        $savedResults = TournamentResult::whereIn('match_number_id', $matchIds)->get()->keyBy('rank');
        if ($savedResults->isNotEmpty()) {
            $this->juara1_id = isset($savedResults[1]) ? $this->findAthleteIdByName($savedResults[1]->athlete_names) : null;
            $this->juara2_id = isset($savedResults[2]) ? $this->findAthleteIdByName($savedResults[2]->athlete_names) : null;

            $participantCount = count($this->randoriAthletes);
            if ($participantCount === 3) {
                $this->juara3_id = isset($savedResults[4]) ? $this->findAthleteIdByName($savedResults[4]->athlete_names) : null;
                $this->juara3_bersama_id = null;
            } else {
                $this->juara3_id = isset($savedResults[3]) ? $this->findAthleteIdByName($savedResults[3]->athlete_names) : null;
                $this->juara3_bersama_id = isset($savedResults[4]) ? $this->findAthleteIdByName($savedResults[4]->athlete_names) : null;
            }
        } else {
            // Auto generate initially
            $this->autoGenerateRandoriWinners();
        }

        $this->showRandoriModal = true;
    }

    private function findAthleteIdByName(string $name): ?int
    {
        foreach ($this->randoriAthletes as $ath) {
            if ($ath['name'] === $name) {
                return $ath['id'];
            }
        }

        return null;
    }

    public function autoGenerateRandoriWinners(): void
    {
        $match = MatchNumber::find($this->randoriMatchId);
        if (! $match) {
            return;
        }

        $matchIds = [$match->id];
        $mergeDetails = DB::table('match_number_merge_details')
            ->where('match_number_id', $match->id)
            ->first();

        if ($mergeDetails) {
            $matchIds = DB::table('match_number_merge_details')
                ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                ->pluck('match_number_id')
                ->toArray();
        }

        $data = $match->drawing_data ?? [];
        $juara = $data['juara'] ?? [];

        // If 'juara' is empty in drawing_data, try to compute it like in randoriConfirmChampion
        if (empty($juara)) {
            $bracketType = $data['bracket_type'] ?? $data['type'] ?? 'single_elimination';
            if ($bracketType === 'double_elimination' && (empty($data['lower_bracket']['rounds']) || ! isset($data['lower_bracket']['rounds']))) {
                $bracketType = 'single_elimination';
            }

            if ($bracketType === 'single_elimination') {
                $ubRounds = $data['upper_bracket']['rounds'] ?? [];
                $ubRoundCount = count($ubRounds);
                if ($ubRoundCount >= 1) {
                    $finalMatch = $ubRounds[$ubRoundCount - 1][0] ?? null;
                    if ($finalMatch && ($finalMatch['winner'] ?? null)) {
                        $juara[1] = $finalMatch['winner_data'];
                        $juara[2] = ($finalMatch['winner'] === 'athlete1') ? $finalMatch['athlete2'] : $finalMatch['athlete1'];
                    }
                }
            } else {
                $gf = $data['grand_final'] ?? null;
                if ($gf && ($gf['winner'] ?? null)) {
                    $juara[1] = $gf['winner_data'];
                    $juara[2] = ($gf['winner'] === 'athlete1') ? $gf['athlete2'] : $gf['athlete1'];
                }
            }
        }

        // Collect all real athletes in the bracket to determine total participants
        $allAthletes = [];
        $bracketSources = [
            $data['upper_bracket']['rounds'] ?? [],
            $data['lower_bracket']['rounds'] ?? [],
        ];
        foreach ($bracketSources as $rounds) {
            foreach ($rounds as $round) {
                foreach ($round as $m) {
                    foreach (['athlete1', 'athlete2'] as $slot) {
                        $a = $m[$slot] ?? null;
                        if ($a && isset($a['id']) && $a['id'] !== 'BYE') {
                            $allAthletes[(string) $a['id']] = $a;
                        }
                    }
                }
            }
        }
        if (isset($data['grand_final'])) {
            foreach (['athlete1', 'athlete2'] as $slot) {
                $a = $data['grand_final'][$slot] ?? null;
                if ($a && isset($a['id']) && $a['id'] !== 'BYE') {
                    $allAthletes[(string) $a['id']] = $a;
                }
            }
        }

        $bracketType = $data['bracket_type'] ?? $data['type'] ?? 'single_elimination';
        if ($bracketType === 'double_elimination' && (empty($data['lower_bracket']['rounds']) || ! isset($data['lower_bracket']['rounds']))) {
            $bracketType = 'single_elimination';
        }
        $participantCount = count($allAthletes);

        if ($bracketType === 'double_elimination') {
            $lbFinalLoser = null;
            $lbSemiLoser = null;

            $lbRounds = $data['lower_bracket']['rounds'] ?? [];
            $lbRoundCount = count($lbRounds);

            if ($lbRoundCount >= 1) {
                $lastRoundMatches = $lbRounds[$lbRoundCount - 1];
                $m = $lastRoundMatches[0] ?? null;
                if ($m && ($m['winner'] ?? null)) {
                    $loserSlot = $m['winner'] === 'athlete1' ? 'athlete2' : 'athlete1';
                    $lbFinalLoser = $m[$loserSlot] ?? null;
                    if ($lbFinalLoser && ($lbFinalLoser['id'] ?? '') === 'BYE') {
                        $lbFinalLoser = null;
                    }
                }
            }

            if ($lbRoundCount >= 2) {
                $semiRoundMatches = $lbRounds[$lbRoundCount - 2];
                $m = $semiRoundMatches[0] ?? null;
                if ($m && ($m['winner'] ?? null)) {
                    $loserSlot = $m['winner'] === 'athlete1' ? 'athlete2' : 'athlete1';
                    $lbSemiLoser = $m[$loserSlot] ?? null;
                    if ($lbSemiLoser && ($lbSemiLoser['id'] ?? '') === 'BYE') {
                        $lbSemiLoser = null;
                    }
                }
            }

            if ($lbFinalLoser) {
                $juara['3'] = $lbFinalLoser;
            }
            if ($lbSemiLoser && $participantCount >= 4) {
                $juara['3.1'] = $lbSemiLoser;
            }
        } elseif ($bracketType === 'single_elimination') {
            $ubRounds = $data['upper_bracket']['rounds'] ?? [];
            $ubRoundCount = count($ubRounds);

            if ($ubRoundCount >= 2) {
                $semiRound = $ubRounds[$ubRoundCount - 2];
                if (isset($semiRound[0])) {
                    $match0 = $semiRound[0];
                    if ($match0 && ($match0['winner'] ?? null)) {
                        $loserSlot = $match0['winner'] === 'athlete1' ? 'athlete2' : 'athlete1';
                        $loser0 = $match0[$loserSlot] ?? null;
                        if ($loser0 && ($loser0['id'] ?? '') !== 'BYE') {
                            $juara['3'] = $loser0;
                        }
                    }
                }
                if (isset($semiRound[1])) {
                    $match1 = $semiRound[1];
                    if ($match1 && ($match1['winner'] ?? null)) {
                        $loserSlot = $match1['winner'] === 'athlete1' ? 'athlete2' : 'athlete1';
                        $loser1 = $match1[$loserSlot] ?? null;
                        if ($loser1 && ($loser1['id'] ?? '') !== 'BYE') {
                            $juara['4'] = $loser1;
                        }
                    }
                }
            }
        }

        // Populate local selects
        $this->juara1_id = isset($juara[1]['id']) ? (int) $juara[1]['id'] : null;
        $this->juara2_id = isset($juara[2]['id']) ? (int) $juara[2]['id'] : null;

        $this->juara3_id = null;
        $this->juara3_bersama_id = null;

        if (isset($juara[3]['id']) && $juara[3]['id'] !== 'BYE') {
            $this->juara3_id = (int) $juara[3]['id'];
        }

        if (isset($juara['3.1']['id']) && $juara['3.1']['id'] !== 'BYE') {
            $this->juara3_bersama_id = (int) $juara['3.1']['id'];
        } elseif (isset($juara[4]['id']) && $juara[4]['id'] !== 'BYE') {
            $this->juara3_bersama_id = (int) $juara[4]['id'];
        }
    }

    public function saveRandoriResult(): void
    {
        if (! $this->randoriMatchId) {
            return;
        }

        $matchNumber = MatchNumber::find($this->randoriMatchId);
        if (! $matchNumber) {
            return;
        }

        $matchIds = [$matchNumber->id];
        $mergeDetails = DB::table('match_number_merge_details')
            ->where('match_number_id', $matchNumber->id)
            ->first();

        if ($mergeDetails) {
            $matchIds = DB::table('match_number_merge_details')
                ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                ->pluck('match_number_id')
                ->toArray();
        }

        // Delete old results for all affected matches
        TournamentResult::whereIn('match_number_id', $matchIds)->delete();

        $participantCount = count($this->randoriAthletes);
        $juaraList = [];
        if ($this->juara1_id) {
            $juaraList[1] = $this->getAthleteDetailsById($this->juara1_id);
        }
        if ($this->juara2_id) {
            $juaraList[2] = $this->getAthleteDetailsById($this->juara2_id);
        }
        if ($this->juara3_id) {
            $targetRank = ($participantCount === 3) ? 4 : 3;
            $juaraList[$targetRank] = $this->getAthleteDetailsById($this->juara3_id);
        }
        if ($this->juara3_bersama_id) {
            $juaraList[4] = $this->getAthleteDetailsById($this->juara3_bersama_id);
        }

        foreach ($matchIds as $mid) {
            $mn = ($mid == $matchNumber->id) ? $matchNumber : MatchNumber::find($mid);
            if (! $mn) {
                continue;
            }

            foreach ($juaraList as $rank => $data) {
                if (! $data) {
                    continue;
                }
                TournamentResult::create([
                    'match_number_id' => $mn->id,
                    'draft_type' => $mn->draft_type,
                    'rank' => $rank,
                    'registration_id' => $data['registration_id'] ?? null,
                    'athlete_names' => $data['name'],
                    'contingent_name' => $data['contingent'],
                    'penyisihan_score' => 0,
                    'final_score' => 0,
                    'accumulated_score' => 0,
                    'generated_by' => Auth::user()?->name ?? 'System',
                    'confirmed_at' => now(),
                ]);
            }
        }

        // Update the drawing_data's juara field as well to sync the bracket screen!
        $data = $matchNumber->drawing_data ?? [];
        $data['juara'] = [];
        if (isset($juaraList[1])) {
            $data['juara'][1] = ['id' => $juaraList[1]['id'], 'name' => $juaraList[1]['name'], 'contingent' => $juaraList[1]['contingent'], 'registration_id' => $juaraList[1]['registration_id']];
        }
        if (isset($juaraList[2])) {
            $data['juara'][2] = ['id' => $juaraList[2]['id'], 'name' => $juaraList[2]['name'], 'contingent' => $juaraList[2]['contingent'], 'registration_id' => $juaraList[2]['registration_id']];
        }
        if (isset($juaraList[3])) {
            $data['juara'][3] = ['id' => $juaraList[3]['id'], 'name' => $juaraList[3]['name'], 'contingent' => $juaraList[3]['contingent'], 'registration_id' => $juaraList[3]['registration_id']];
        }
        if (isset($juaraList[4])) {
            $data['juara'][4] = ['id' => $juaraList[4]['id'], 'name' => $juaraList[4]['name'], 'contingent' => $juaraList[4]['contingent'], 'registration_id' => $juaraList[4]['registration_id']];
        }

        $matchNumber->update(['drawing_data' => $data]);

        $this->showRandoriModal = false;
        $this->randoriMatchId = null;

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '✅ Berhasil!',
            'text' => 'Hasil pertandingan Randori berhasil disimpan.',
        ]);
    }

    private function getAthleteDetailsById(int $id): ?array
    {
        foreach ($this->randoriAthletes as $ath) {
            if ($ath['id'] == $id) {
                return $ath;
            }
        }

        return null;
    }

    public function render()
    {
        $parentView = parent::render();
        $data = $parentView->getData();

        return view('livewire.admin.new-laporan-hasil-index', $data);
    }
}
