<?php

namespace App\Livewire\Admin\Registration;

use App\Models\Registration;
use App\Models\Technique\Technique;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AdminRegistrationShow extends Component
{
    public $registration;
    public $allTechniques;

    public function mount($registration)
    {
        $this->registration = Registration::with([
            'contingent', 
            'officials', 
            'athletes.matchNumbers'
        ])->findOrFail($registration);

        $this->allTechniques = Technique::pluck('name', 'id')->toArray();
    }

    public function verify()
    {
        $this->registration->update(['status' => 'verified']);
        
        $this->dispatch('swal', [
            'title' => 'Terverifikasi!',
            'text' => 'Pendaftaran telah berhasil diverifikasi.',
            'icon' => 'success',
        ]);
    }

    public function reject()
    {
        $this->registration->update(['status' => 'rejected']);
        
        $this->dispatch('swal', [
            'title' => 'Ditolak!',
            'text' => 'Pendaftaran telah ditolak.',
            'icon' => 'warning',
        ]);
    }

    public function getFeeDetailsProperty()
    {
        $contingentFee = 2500000;
        $ageGroups = \App\Models\Group\AgeGroup::pluck('price', 'name')->toArray();
        
        $athleteFees = [];
        foreach ($this->registration->athletes as $athlete) {
            $groupName = $athlete->pivot->age_group;
            $price = $ageGroups[$groupName] ?? 0;
            
            if (!isset($athleteFees[$groupName])) {
                $athleteFees[$groupName] = [
                    'count' => 0,
                    'price' => $price,
                    'total' => 0
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

    public function getGroupedMatchesProperty()
    {
        $matches = [];
        foreach ($this->registration->athletes as $athlete) {
            // Filter match numbers that belong to THIS registration
            $athleteMatches = $athlete->matchNumbers->where('pivot.registration_id', $this->registration->id);

            foreach ($athleteMatches as $match) {
                $mId = $match->id;
                if (!isset($matches[$mId])) {
                    $matches[$mId] = [
                        'details' => $match,
                        'techniques' => json_decode($match->pivot->technique_ids ?? '[]', true),
                        'athletes' => []
                    ];
                }
                $matches[$mId]['athletes'][] = $athlete;
            }
        }
        return $matches;
    }

    public function render()
    {
        return view('livewire.admin.registration.admin-registration-show', [
            'groupedMatches' => $this->groupedMatches,
        ]);
    }
}
