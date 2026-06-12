<?php

namespace App\Livewire\Admin;

use App\Models\Athlete;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\Technique\Technique;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NewRegistrationShow extends Component
{
    public $registration;

    public $allTechniques;

    // Editing Athlete properties
    public ?int $editingAthleteId = null;

    public bool $isAddingAthlete = false;

    public ?int $editingMatchNumberId = null;

    public array $selectedTechniqueIds = [];

    public string $newTechniqueId = '';

    public string $editName = '';

    public string $editNik = '';

    public string $editNikKenshi = '';

    public string $editGender = '';

    public float $editWeight = 0;

    public string $editRank = '';

    public string $editAgeGroup = '';

    public string $editDojo = '';

    public string $editBpjsNumber = '';

    public string $editBpjsStatus = 'Aktif';

    // Event selections
    public $editEvent1 = '';

    public $editEvent2 = '';

    public $editEvent3 = '';

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

    public function openAddAthlete(): void
    {
        $this->isAddingAthlete = true;
        $this->editingAthleteId = null;

        // Reset fields
        $this->editName = '';
        $this->editNik = '';
        $this->editNikKenshi = '';
        $this->editGender = 'Male';
        $this->editWeight = 0;
        $this->editRank = 'Kyu 5';
        $this->editAgeGroup = '';
        $this->editDojo = $this->registration->contingent?->name ?? '';
        $this->editBpjsNumber = '';
        $this->editBpjsStatus = 'Aktif';
        $this->editEvent1 = '';
        $this->editEvent2 = '';
        $this->editEvent3 = '';

        $this->dispatch('open-edit-modal');
    }

    public function openEditAthlete(int $athleteId): void
    {
        $this->isAddingAthlete = false;
        $athlete = Athlete::findOrFail($athleteId);
        $pivot = $this->registration->athletes()->where('athlete_id', $athleteId)->first()->pivot;

        $this->editingAthleteId = $athleteId;
        $this->editName = $athlete->name;
        $this->editNik = $athlete->nik ?? '';
        $this->editNikKenshi = $athlete->nik_kenshi ?? '';
        $this->editGender = $athlete->gender ?? 'Male';
        $this->editWeight = (float) ($pivot->weight ?? 0);
        $this->editRank = $pivot->rank ?? $pivot->kyu ?? 'Kyu 5';
        $this->editAgeGroup = $pivot->age_group ?? '';
        $this->editDojo = $pivot->dojo_origin ?? $athlete->dojo_origin ?? '';
        $this->editBpjsNumber = $athlete->bpjs_number ?? '';
        $this->editBpjsStatus = $athlete->bpjs_status ?? 'Aktif';

        // Load existing events
        $events = $athlete->matchNumbers()->wherePivot('registration_id', $this->registration->id)->pluck('match_number_id')->toArray();
        $this->editEvent1 = $events[0] ?? '';
        $this->editEvent2 = $events[1] ?? '';
        $this->editEvent3 = $events[2] ?? '';

        $this->dispatch('open-edit-modal');
    }

    public function saveAthlete(): void
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editNik' => 'required|numeric|digits:16',
            'editNikKenshi' => 'nullable|string|max:255',
            'editGender' => 'required|in:Male,Female',
            'editWeight' => 'required|numeric',
            'editRank' => 'required|string',
            'editAgeGroup' => 'required|string',
            'editDojo' => 'required|string',
            'editBpjsNumber' => 'required|numeric',
            'editBpjsStatus' => 'required|in:Aktif,Non-Aktif',
            'editEvent1' => 'nullable|exists:match_numbers,id',
            'editEvent2' => 'nullable|exists:match_numbers,id',
            'editEvent3' => 'nullable|exists:match_numbers,id',
        ]);

        if ($this->isAddingAthlete) {
            DB::transaction(function () {
                // 1. Create or retrieve Master athlete record by NIK
                $athlete = Athlete::firstOrCreate(
                    ['nik' => $this->editNik],
                    [
                        'name' => $this->editName,
                        'nik_kenshi' => $this->editNikKenshi,
                        'gender' => $this->editGender,
                        'birth_place' => $this->registration->contingent?->kab_kota ?? '-',
                        'birth_date' => '2000-01-01', // fallback default
                        'address' => $this->registration->contingent?->address ?? '-',
                        'bpjs_number' => $this->editBpjsNumber,
                        'bpjs_status' => $this->editBpjsStatus,
                    ]
                );

                // Ensure contingent link
                $athlete->contingents()->syncWithoutDetaching([
                    $this->registration->contingent_id => ['is_primary' => true],
                ]);

                // 2. Attach to registration
                $this->registration->athletes()->attach($athlete->id, [
                    'weight' => $this->editWeight,
                    'kyu' => $this->editRank,
                    'rank' => $this->editRank,
                    'age_group' => $this->editAgeGroup,
                    'dojo_origin' => $this->editDojo,
                    'city' => $this->registration->contingent?->kab_kota ?? '-',
                    'match_type' => 'Tanding',
                ]);

                // 3. Attach selected events
                $selectedEvents = array_filter([$this->editEvent1, $this->editEvent2, $this->editEvent3]);
                foreach (array_unique($selectedEvents) as $eventId) {
                    $athlete->matchNumbers()->attach($eventId, [
                        'registration_id' => $this->registration->id,
                    ]);
                }

                // 4. Recalculate fees
                $this->recalculateRegistrationFees($this->registration);

                // 5. Reset status
                $this->registration->update(['athlete_status' => 'pending']);
            });

            $this->isAddingAthlete = false;
            $this->registration->load(['athletes.matchNumbers', 'officials']);

            $this->dispatch('close-edit-modal');
            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Atlet baru berhasil didaftarkan dan ditambahkan ke pendaftaran ini.',
                'icon' => 'success',
            ]);

            return;
        }

        $athlete = Athlete::findOrFail($this->editingAthleteId);

        DB::transaction(function () use ($athlete) {
            // 1. Update Master data
            $athlete->update([
                'name' => $this->editName,
                'nik' => $this->editNik,
                'nik_kenshi' => $this->editNikKenshi,
                'gender' => $this->editGender,
                'bpjs_number' => $this->editBpjsNumber,
                'bpjs_status' => $this->editBpjsStatus,
            ]);

            // 2. Update registration context pivot
            $this->registration->athletes()->updateExistingPivot($athlete->id, [
                'weight' => $this->editWeight,
                'kyu' => $this->editRank,
                'rank' => $this->editRank,
                'age_group' => $this->editAgeGroup,
                'dojo_origin' => $this->editDojo,
            ]);

            // 3. Update events registrations
            $athlete->matchNumbers()->wherePivot('registration_id', $this->registration->id)->detach();

            $selectedEvents = array_filter([$this->editEvent1, $this->editEvent2, $this->editEvent3]);
            foreach (array_unique($selectedEvents) as $eventId) {
                $athlete->matchNumbers()->attach($eventId, [
                    'registration_id' => $this->registration->id,
                ]);
            }

            // 4. Recalculate fees
            $this->recalculateRegistrationFees($this->registration);

            // 5. Reset status
            $this->registration->update(['athlete_status' => 'pending']);
        });

        $this->editingAthleteId = null;

        // Refresh registration model
        $this->registration->load(['athletes.matchNumbers', 'officials']);

        $this->dispatch('close-edit-modal');
        $this->dispatch('swal', [
            'title' => 'Tersimpan!',
            'text' => 'Data kenshi berhasil diperbarui. Status verifikasi di-reset ke Pending dan biaya pendaftaran diperbarui.',
            'icon' => 'success',
        ]);
    }

    public function deleteAthlete(int $athleteId): void
    {
        $athlete = Athlete::findOrFail($athleteId);

        DB::transaction(function () use ($athleteId) {
            // 1. Detach from registration
            $this->registration->athletes()->detach($athleteId);

            // 2. Detach from events for this registration
            DB::table('athlete_match_number')
                ->where('registration_id', $this->registration->id)
                ->where('athlete_id', $athleteId)
                ->delete();

            // 3. Recalculate fees
            $this->recalculateRegistrationFees($this->registration);

            // 4. Reset status
            $this->registration->update(['athlete_status' => 'pending']);
        });

        // Refresh registration model
        $this->registration->load(['athletes.matchNumbers', 'officials']);

        $this->dispatch('swal', [
            'title' => 'Dihapus!',
            'text' => 'Atlet telah dihapus dari pendaftaran ini, biaya pendaftaran telah diperbarui.',
            'icon' => 'success',
        ]);
    }

    private function recalculateRegistrationFees(Registration $registration): void
    {
        $contingentFee = 2500000;
        $ageGroups = AgeGroup::pluck('price', 'name')->toArray();

        $athleteFeeSum = 0;
        foreach ($registration->athletes()->get() as $athlete) {
            $groupName = $athlete->pivot->age_group;
            $athleteFeeSum += $ageGroups[$groupName] ?? 0;
        }

        $totalCost = $contingentFee + $athleteFeeSum;
        $finalAmount = $totalCost + (int) $registration->unique_code;

        $registration->update([
            'total_cost' => $totalCost,
            'final_amount' => $finalAmount,
        ]);
    }

    public function getAvailableEventsProperty(): array
    {
        $query = MatchNumber::with('ageGroup')->where(function ($q) {
            $q->where('gender', $this->editGender)
                ->orWhere('gender', 'Mix');
        });

        if (! empty($this->editAgeGroup)) {
            $ageGroup = AgeGroup::where('name', $this->editAgeGroup)->first();
            if ($ageGroup) {
                $query->where('age_group_id', $ageGroup->id);
            }
        }

        return $query->orderBy('name')
            ->get()
            ->map(function ($mn) {
                $ageGroupName = $mn->ageGroup?->name ? ' - '.$mn->ageGroup->name : '';

                return [
                    'id' => $mn->id,
                    'name' => $mn->name.$ageGroupName,
                    'gender' => $mn->gender,
                    'draft_type' => $mn->draft_type,
                ];
            })
            ->toArray();
    }

    public function getAgeGroupsListProperty(): array
    {
        return AgeGroup::orderBy('order')->pluck('name')->toArray();
    }

    public function openEditTechniques(int $matchNumberId): void
    {
        $this->editingMatchNumberId = $matchNumberId;

        // Load existing techniques from the first athlete in this match
        $firstAthleteMatch = DB::table('athlete_match_number')
            ->where('registration_id', $this->registration->id)
            ->where('match_number_id', $matchNumberId)
            ->first();

        $this->selectedTechniqueIds = $firstAthleteMatch
            ? (json_decode($firstAthleteMatch->technique_ids ?? '[]', true) ?? [])
            : [];

        $this->newTechniqueId = '';
        $this->dispatch('open-techniques-modal');
    }

    public function addTechnique(): void
    {
        if (empty($this->newTechniqueId)) {
            return;
        }

        $this->selectedTechniqueIds[] = (int) $this->newTechniqueId;
        $this->newTechniqueId = '';
    }

    public function removeTechnique(int $index): void
    {
        if (isset($this->selectedTechniqueIds[$index])) {
            unset($this->selectedTechniqueIds[$index]);
            $this->selectedTechniqueIds = array_values($this->selectedTechniqueIds);
        }
    }

    public function moveTechniqueUp(int $index): void
    {
        if ($index > 0 && isset($this->selectedTechniqueIds[$index])) {
            $temp = $this->selectedTechniqueIds[$index - 1];
            $this->selectedTechniqueIds[$index - 1] = $this->selectedTechniqueIds[$index];
            $this->selectedTechniqueIds[$index] = $temp;
        }
    }

    public function moveTechniqueDown(int $index): void
    {
        if ($index < count($this->selectedTechniqueIds) - 1 && isset($this->selectedTechniqueIds[$index])) {
            $temp = $this->selectedTechniqueIds[$index + 1];
            $this->selectedTechniqueIds[$index + 1] = $this->selectedTechniqueIds[$index];
            $this->selectedTechniqueIds[$index] = $temp;
        }
    }

    public function saveTechniques(): void
    {
        DB::table('athlete_match_number')
            ->where('registration_id', $this->registration->id)
            ->where('match_number_id', $this->editingMatchNumberId)
            ->update([
                'technique_ids' => json_encode($this->selectedTechniqueIds),
                'updated_at' => now(),
            ]);

        $this->editingMatchNumberId = null;

        // Refresh registration
        $this->registration->load(['athletes.matchNumbers', 'officials']);

        $this->dispatch('close-techniques-modal');
        $this->dispatch('swal', [
            'title' => 'Tersimpan!',
            'text' => 'Komposisi teknik berhasil diperbarui.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.new-registration-show', [
            'groupedMatches' => $this->groupedMatches,
        ])->layout('layouts.premium', ['title' => 'Detail Registrasi']);
    }
}
