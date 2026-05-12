<?php

namespace App\Livewire\Contingent;

use App\Models\Group\AgeGroup;
use App\Models\Registration;
use App\Models\Technique\Technique;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.premium')]
class RegistrationHistoryDetailIndex extends Component
{
    public $registration;

    public $allTechniques;

    public function mount($registration): void
    {
        $contingent = Auth::user()->contingent;

        if (! $contingent) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $this->registration = Registration::with([
            'contingent',
            'officials',
            'athletes.matchNumbers',
        ])->where('contingent_id', $contingent->id)
            ->findOrFail($registration);

        $this->allTechniques = Technique::pluck('name', 'id')->toArray();
    }

    public function getFeeDetailsProperty(): array
    {
        $contingentFee = 2500000;
        $ageGroups = AgeGroup::pluck('price', 'name')->toArray();

        $athleteFees = [];
        foreach ($this->registration->athletes as $athlete) {
            $groupName = $athlete->pivot->age_group;
            $price = $ageGroups[$groupName] ?? 0;

            if (! isset($athleteFees[$groupName])) {
                $athleteFees[$groupName] = [
                    'count' => 0,
                    'price' => $price,
                    'total' => 0,
                ];
            }
            $athleteFees[$groupName]['count']++;
            $athleteFees[$groupName]['total'] += $price;
        }

        return [
            'contingent_fee' => $contingentFee,
            'athlete_fees' => $athleteFees,
            'unique_code' => $this->registration->unique_code,
            'total_cost' => $this->registration->total_cost,
            'final_amount' => $this->registration->final_amount,
        ];
    }

    public function getGroupedMatchesProperty(): array
    {
        $matches = [];

        foreach ($this->registration->athletes as $athlete) {
            $athleteMatches = $athlete->matchNumbers()
                ->wherePivot('registration_id', $this->registration->id)
                ->orderBy('name')
                ->get();

            foreach ($athleteMatches as $match) {
                $mId = $match->id;

                if (! isset($matches[$mId])) {
                    $matches[$mId] = [
                        'details' => $match,
                        'techniques' => json_decode($match->pivot->technique_ids ?? '[]', true),
                        'athletes' => [],
                    ];
                }

                $matches[$mId]['athletes'][] = $athlete;
            }
        }

        $matches = array_values($matches);
        usort($matches, function ($a, $b) {
            return strcmp($a['details']->name, $b['details']->name);
        });

        return $matches;
    }

    public function render()
    {
        return view('livewire.contingent.registration-history-detail-index', [
            'groupedMatches' => $this->groupedMatches,
        ])->title('Detail Riwayat Pendaftaran');
    }
}
