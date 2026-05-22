<?php

namespace App\Livewire\Admin;

use App\Models\Group\AgeGroup;
use App\Models\Registration;
use App\Models\Technique\Technique;
use Livewire\Component;

class NewRegistrationShow extends Component
{
    public $registration;

    public $allTechniques;

    public function mount($registration): void
    {
        $this->registration = Registration::with([
            'contingent',
            'officials',
            'athletes.matchNumbers',
        ])->findOrFail($registration);

        $this->allTechniques = Technique::pluck('name', 'id')->toArray();
    }

    public function verify(): void
    {
        $this->registration->update(['status' => 'verified']);

        $this->dispatch('swal', [
            'title' => 'Terverifikasi!',
            'text' => 'Pendaftaran telah berhasil diverifikasi.',
            'icon' => 'success',
        ]);
    }

    public function reject(): void
    {
        $this->registration->update(['status' => 'rejected']);

        $this->dispatch('swal', [
            'title' => 'Ditolak!',
            'text' => 'Pendaftaran telah ditolak.',
            'icon' => 'warning',
        ]);
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
                        'max_athletes' => $match->max_athletes,
                        'techniques' => json_decode($match->pivot->technique_ids ?? '[]', true),
                        'athletes' => [],
                    ];
                }

                $matches[$mId]['athletes'][] = [
                    'model' => $athlete,
                    'techniques' => json_decode($match->pivot->technique_ids ?? '[]', true),
                ];
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
        return view('livewire.admin.new-registration-show', [
            'groupedMatches' => $this->groupedMatches,
        ])->layout('layouts.premium', ['title' => 'Detail Registrasi']);
    }
}
