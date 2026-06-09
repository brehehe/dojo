<?php

namespace App\Livewire\Contingent;

use App\Models\Athlete;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

/**
 * Class AthleteVerificationIndex
 * Handles the athlete data review and update panel for Contingents.
 */
class AthleteVerificationIndex extends Component
{
    public $contingent;

    public $selectedRegistrationId;

    // Editing Athlete properties
    public ?int $editingAthleteId = null;

    public string $editName = '';

    public string $editNik = '';

    public string $editNikKenshi = '';

    public string $editGender = '';

    public float $editWeight = 0;

    public string $editRank = '';

    public string $editDojo = '';

    public string $editBpjsNumber = '';

    public string $editBpjsStatus = 'Aktif';

    // Event selections
    public $editEvent1 = '';

    public $editEvent2 = '';

    public $editEvent3 = '';

    public function mount(): void
    {
        $user = Auth::user();
        if (! $user || ! $user->contingent()->exists()) {
            redirect()->route('contingent.setup');

            return;
        }

        $this->contingent = $user->contingent;

        // Auto-select latest verified or pending registration
        $latestReg = Registration::where('contingent_id', $this->contingent->id)
            ->whereIn('status', ['verified', 'pending'])
            ->latest()
            ->first();

        if ($latestReg) {
            $this->selectedRegistrationId = $latestReg->id;
        }
    }

    /**
     * Confirms that athlete data is correct and ready for verification.
     */
    public function confirmDataCorrect(int $registrationId): void
    {
        $registration = Registration::where('contingent_id', $this->contingent->id)
            ->findOrFail($registrationId);

        $registration->update(['athlete_status' => 'pending']);

        $this->dispatch('swal', [
            'title' => 'Terkirim!',
            'text' => 'Data atlet Anda telah dikonfirmasi dan diajukan untuk verifikasi Panitia.',
            'icon' => 'success',
            'timer' => 3000,
        ]);
    }

    /**
     * Opens modal to edit athlete details.
     */
    public function openEditAthlete(int $athleteId): void
    {
        $athlete = Athlete::findOrFail($athleteId);
        $registration = Registration::where('contingent_id', $this->contingent->id)
            ->findOrFail($this->selectedRegistrationId);
        $pivot = $registration->athletes()->where('athlete_id', $athleteId)->first()->pivot;

        $this->editingAthleteId = $athleteId;
        $this->editName = $athlete->name;
        $this->editNik = $athlete->nik ?? '';
        $this->editNikKenshi = $athlete->nik_kenshi ?? '';
        $this->editGender = $athlete->gender ?? 'Male';
        $this->editWeight = (float) ($pivot->weight ?? 0);
        $this->editRank = $pivot->rank ?? $pivot->kyu ?? 'Kyu 5';
        $this->editDojo = $pivot->dojo_origin ?? $athlete->dojo_origin ?? '';
        $this->editBpjsNumber = $athlete->bpjs_number ?? '';
        $this->editBpjsStatus = $athlete->bpjs_status ?? 'Aktif';

        // Load existing events
        $events = $athlete->matchNumbers()->wherePivot('registration_id', $registration->id)->pluck('match_number_id')->toArray();
        $this->editEvent1 = $events[0] ?? '';
        $this->editEvent2 = $events[1] ?? '';
        $this->editEvent3 = $events[2] ?? '';

        $this->dispatch('open-edit-modal');
    }

    /**
     * Get list of match number events that this athlete is eligible to join.
     */
    public function getAvailableEventsProperty(): array
    {
        if (! $this->editingAthleteId) {
            return [];
        }

        $athlete = Athlete::find($this->editingAthleteId);
        if (! $athlete) {
            return [];
        }

        // Fetch matches based on current gender
        return MatchNumber::where(function ($q) use ($athlete) {
            $q->where('gender', $athlete->gender)
                ->orWhere('gender', 'Mix');
        })
            ->orderBy('name')
            ->get(['id', 'name', 'gender', 'draft_type'])
            ->toArray();
    }

    /**
     * Saves edits for the athlete details.
     */
    public function saveAthlete(): void
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editNik' => 'required|numeric|digits:16',
            'editNikKenshi' => 'nullable|string|max:255',
            'editGender' => 'required|in:Male,Female',
            'editWeight' => 'required|numeric',
            'editRank' => 'required|string',
            'editDojo' => 'required|string',
            'editBpjsNumber' => 'required|numeric',
            'editBpjsStatus' => 'required|in:Aktif,Non-Aktif',
            'editEvent1' => 'nullable|exists:match_numbers,id',
            'editEvent2' => 'nullable|exists:match_numbers,id',
            'editEvent3' => 'nullable|exists:match_numbers,id',
        ]);

        $athlete = Athlete::findOrFail($this->editingAthleteId);
        $registration = Registration::where('contingent_id', $this->contingent->id)
            ->findOrFail($this->selectedRegistrationId);

        DB::transaction(function () use ($athlete, $registration) {
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
            $registration->athletes()->updateExistingPivot($athlete->id, [
                'weight' => $this->editWeight,
                'kyu' => $this->editRank,
                'rank' => $this->editRank,
                'dojo_origin' => $this->editDojo,
            ]);

            // 3. Update events registrations
            // Clear existing events for this registration and athlete
            $athlete->matchNumbers()->wherePivot('registration_id', $registration->id)->detach();

            // Re-attach selected events
            $selectedEvents = array_filter([$this->editEvent1, $this->editEvent2, $this->editEvent3]);
            foreach (array_unique($selectedEvents) as $eventId) {
                $athlete->matchNumbers()->attach($eventId, [
                    'registration_id' => $registration->id,
                ]);
            }

            // 4. Force status to pending since data has changed
            $registration->update(['athlete_status' => 'pending']);
        });

        $this->editingAthleteId = null;

        $this->dispatch('close-edit-modal');
        $this->dispatch('swal', [
            'title' => 'Tersimpan!',
            'text' => 'Data kenshi berhasil diperbarui. Status verifikasi di-reset ke Pending.',
            'icon' => 'success',
            'timer' => 3000,
        ]);
    }

    public function render()
    {
        // Get all registrations
        $registrations = Registration::where('contingent_id', $this->contingent->id)
            ->whereIn('status', ['verified', 'pending'])
            ->latest()
            ->get();

        // Get active selected registration
        $activeRegistration = null;
        if ($this->selectedRegistrationId) {
            $activeRegistration = Registration::with(['athletes.matchNumbers', 'officials'])
                ->where('contingent_id', $this->contingent->id)
                ->find($this->selectedRegistrationId);
        }

        return view('livewire.contingent.athlete-verification-index', [
            'registrations' => $registrations,
            'activeRegistration' => $activeRegistration,
        ])->layout('layouts.premium', ['title' => 'Laporan & Verifikasi Atlet']);
    }
}
