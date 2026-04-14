<?php

namespace App\Livewire\Admin\Arbitrase\Scoring;

use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AdminArbitraseScoringEmbuDetail extends Component
{
    public MatchNumber $matchNumber;

    public $scores = []; // judge_1 to judge_5

    public $activeRegistrationId = null;

    public $showModal = false;

    public function mount(MatchNumber $matchNumber)
    {
        $this->matchNumber = $matchNumber->load(['athletes', 'embuScores']);
    }

    public function callParticipant($registrationId)
    {
        $this->matchNumber->update(['active_registration_id' => $registrationId]);
        
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Peserta Dipanggil',
            'text' => 'Layar semua wasit kini menampilkan form untuk peserta ini.',
        ]);
    }

    public function openScoringModal($registrationId)
    {
        $this->activeRegistrationId = $registrationId;
        $existing = EmbuScore::where('match_number_id', $this->matchNumber->id)
            ->where('registration_id', $registrationId)
            ->first();

        if ($existing) {
            $this->scores = [
                'judge_1' => $existing->judge_1,
                'judge_2' => $existing->judge_2,
                'judge_3' => $existing->judge_3,
                'judge_4' => $existing->judge_4,
                'judge_5' => $existing->judge_5,
            ];
        } else {
            $this->scores = [
                'judge_1' => 0,
                'judge_2' => 0,
                'judge_3' => 0,
                'judge_4' => 0,
                'judge_5' => 0,
            ];
        }

        $this->showModal = true;
    }

    public function saveScore()
    {
        $judge_values = array_values($this->scores);
        sort($judge_values);

        // Sum middle 3
        $total = $judge_values[1] + $judge_values[2] + $judge_values[3];

        EmbuScore::updateOrCreate(
            [
                'match_number_id' => $this->matchNumber->id,
                'registration_id' => $this->activeRegistrationId,
            ],
            array_merge($this->scores, ['total_score' => $total])
        );

        $this->calculateRanks();
        $this->showModal = false;
        $this->matchNumber->load('embuScores');

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Nilai Berhasil Disimpan',
            'text' => 'Total Skor: '.$total,
        ]);
    }

    protected function calculateRanks()
    {
        $scores = EmbuScore::where('match_number_id', $this->matchNumber->id)
            ->orderByDesc('total_score')
            ->get();

        foreach ($scores as $index => $score) {
            $score->update(['rank' => $index + 1]);
        }
    }

    public function render()
    {
        // Group athletes by registration for display
        $registrations = $this->matchNumber->athletes
            ->groupBy('pivot.registration_id')
            ->map(function ($athletes, $regId) {
                $registration = Registration::find($regId);
                $score = $this->matchNumber->embuScores->where('registration_id', $regId)->first();

                return [
                    'id' => $regId,
                    'is_group' => $registration->is_group,
                    'athletes' => $athletes,
                    'contingent' => $registration->contingent,
                    'score' => $score,
                ];
            })
            ->sortByDesc(fn ($item) => $item['score']?->total_score ?? -1)
            ->values();

        return view('livewire.admin.arbitrase.scoring.admin-arbitrase-scoring-embu-detail', [
            'registrations' => $registrations,
        ]);
    }
}
