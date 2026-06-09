<?php

namespace App\Livewire\Admin;

use App\Models\Athlete;
use App\Models\Registration;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class NewRegistrationVerificationIndex
 * Handles the athlete data verification panel for Admins.
 */
class NewRegistrationVerificationIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public int $perPage = 10;

    public array $expanded = [];

    public array $selectedRows = [];

    public bool $selectAll = false;

    // Edit modal properties
    public ?int $editingAthleteId = null;

    public ?int $editingRegistrationId = null;

    public string $editName = '';

    public string $editNik = '';

    public string $editNikKenshi = '';

    public string $editGender = '';

    public float $editWeight = 0;

    public string $editRank = '';

    public string $editDojo = '';

    public string $editBpjsNumber = '';

    public string $editBpjsStatus = 'Aktif';

    protected $queryString = ['search', 'status', 'perPage'];

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->selectedRows = [];
        $this->selectAll = false;
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
        $this->selectedRows = [];
        $this->selectAll = false;
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
        $this->selectedRows = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $this->selectedRows = $this->getFilteredRegistrationsQuery()
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedRows = [];
        }
    }

    public function verifySelected(): void
    {
        if (empty($this->selectedRows)) {
            return;
        }

        Registration::whereIn('id', $this->selectedRows)->update(['athlete_status' => 'verified']);

        $count = count($this->selectedRows);
        $this->selectedRows = [];
        $this->selectAll = false;

        $this->dispatch('swal', [
            'title' => 'Terverifikasi!',
            'text' => "Data atlet dari {$count} pendaftaran berhasil diverifikasi.",
            'icon' => 'success',
            'timer' => 3000,
        ]);
    }

    public function unverifySelected(): void
    {
        if (empty($this->selectedRows)) {
            return;
        }

        Registration::whereIn('id', $this->selectedRows)->update(['athlete_status' => 'pending']);

        $count = count($this->selectedRows);
        $this->selectedRows = [];
        $this->selectAll = false;

        $this->dispatch('swal', [
            'title' => 'Unverified!',
            'text' => "Data atlet dari {$count} pendaftaran diubah status menjadi pending.",
            'icon' => 'warning',
            'timer' => 3000,
        ]);
    }

    /**
     * Toggles the expansion state of a registration row.
     */
    public function toggleExpand(int $registrationId): void
    {
        if (in_array($registrationId, $this->expanded, true)) {
            $this->expanded = array_values(array_diff($this->expanded, [$registrationId]));
        } else {
            $this->expanded[] = $registrationId;
        }
    }

    /**
     * Toggles athlete verification status for a registration.
     */
    public function toggleVerification(int $registrationId): void
    {
        $registration = Registration::findOrFail($registrationId);

        if ($registration->athlete_status === 'verified') {
            $registration->update(['athlete_status' => 'pending']);
            $msg = 'Status verifikasi atlet diubah menjadi PENDING.';
            $icon = 'warning';
        } else {
            $registration->update(['athlete_status' => 'verified']);
            $msg = 'Data atlet berhasil DIVERIFIKASI untuk TM Drawing.';
            $icon = 'success';
        }

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => $msg,
            'icon' => $icon,
            'timer' => 3000,
        ]);
    }

    /**
     * Opens the modal to edit an athlete's registration details.
     */
    public function openEditAthlete(int $athleteId, int $registrationId): void
    {
        $athlete = Athlete::findOrFail($athleteId);
        $registration = Registration::findOrFail($registrationId);
        $pivot = $registration->athletes()->where('athlete_id', $athleteId)->first()->pivot;

        $this->editingAthleteId = $athleteId;
        $this->editingRegistrationId = $registrationId;
        $this->editName = $athlete->name;
        $this->editNik = $athlete->nik ?? '';
        $this->editNikKenshi = $athlete->nik_kenshi ?? '';
        $this->editGender = $athlete->gender ?? 'Male';
        $this->editWeight = (float) ($pivot->weight ?? 0);
        $this->editRank = $pivot->rank ?? $pivot->kyu ?? 'Kyu 5';
        $this->editDojo = $pivot->dojo_origin ?? $athlete->dojo_origin ?? '';
        $this->editBpjsNumber = $athlete->bpjs_number ?? '';
        $this->editBpjsStatus = $athlete->bpjs_status ?? 'Aktif';

        $this->dispatch('open-edit-modal');
    }

    /**
     * Saves the modified athlete data.
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
        ]);

        $athlete = Athlete::findOrFail($this->editingAthleteId);
        $registration = Registration::findOrFail($this->editingRegistrationId);

        // Update Master data
        $athlete->update([
            'name' => $this->editName,
            'nik' => $this->editNik,
            'nik_kenshi' => $this->editNikKenshi,
            'gender' => $this->editGender,
            'bpjs_number' => $this->editBpjsNumber,
            'bpjs_status' => $this->editBpjsStatus,
        ]);

        // Update registration context pivot
        $registration->athletes()->updateExistingPivot($athlete->id, [
            'weight' => $this->editWeight,
            'kyu' => $this->editRank,
            'rank' => $this->editRank,
            'dojo_origin' => $this->editDojo,
        ]);

        // Reset verification status since changes occurred
        $registration->update(['athlete_status' => 'pending']);

        $this->editingAthleteId = null;
        $this->editingRegistrationId = null;

        $this->dispatch('close-edit-modal');
        $this->dispatch('swal', [
            'title' => 'Terupdate!',
            'text' => 'Data atlet berhasil diperbarui. Status verifikasi di-reset ke Pending.',
            'icon' => 'success',
            'timer' => 3000,
        ]);
    }

    /**
     * Get statistics summaries for athlete registrations.
     */
    public function getStats(): array
    {
        return [
            'total' => Registration::where('status', 'verified')->count(),
            'pending' => Registration::where('status', 'verified')->where('athlete_status', 'pending')->count(),
            'verified' => Registration::where('status', 'verified')->where('athlete_status', 'verified')->count(),
        ];
    }

    protected function getFilteredRegistrationsQuery()
    {
        return Registration::where('status', 'verified')
            ->when($this->search, function ($query) {
                $query->where('referral_code', 'ilike', '%'.$this->search.'%')
                    ->orWhereHas('contingent', function ($q) {
                        $q->where('name', 'ilike', '%'.$this->search.'%');
                    });
            })
            ->when($this->status, function ($query) {
                $query->where('athlete_status', $this->status);
            });
    }

    public function render()
    {
        // We only verify athletes from verified payment registrations
        $registrations = $this->getFilteredRegistrationsQuery()
            ->with(['contingent', 'athletes.matchNumbers', 'officials'])
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.new-registration-verification-index', [
            'registrations' => $registrations,
            'stats' => $this->getStats(),
        ])->layout('layouts.premium', ['title' => 'Verifikasi Data Kenshi']);
    }
}
