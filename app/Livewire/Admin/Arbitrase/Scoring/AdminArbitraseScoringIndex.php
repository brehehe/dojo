<?php

namespace App\Livewire\Admin\Arbitrase\Scoring;

use App\Models\ActiveCourtReferee;
use App\Models\Contingent;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Pool\Pool;
use App\Models\Referee;
use App\Models\Rundown\Rundown;
use App\Models\ScheduleReferee;
use App\Models\SessionTime;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminArbitraseScoringIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterCourt = '';

    public string $filterSession = '';

    public string $filterRundown = '';

    public string $filterPool = '';

    public string $filterRound = '';

    public string $filterType = ''; // embu | randori

    public string $filterContingent = '';

    public string $filterAgeGroup = '';

    public string $filterMatchNumber = '';

    public string $filterGender = '';

    // Referee Assignment Properties
    public bool $showRefereeModal = false;

    public $assigningRundownId;

    public $assigningSessionId;

    public $assigningCourtId;

    public $searchReferee = '';

    public $selectedReferees = [];

    /** Reset pagination when any filter changes. */
    public function updated(string $property): void
    {
        if (str_starts_with($property, 'filter') || $property === 'search') {
            $this->resetPage();
        }
    }

    /**
     * Panggil sebuah slot drawing ke lapangannya.
     *
     * Semua informasi konteks (court_id, match_number_id, registration_id,
     * session_time_id, pool_id, rundown_id, draft_type, contingent_id) di-derive
     * langsung dari DrawingMatchNumber — tidak ada parameter redundan.
     */
    public function activateMatch(int $drawingId): void
    {
        $drawing = DrawingMatchNumber::with([
            'matchNumber',
            'court',
            'registration.contingent',
            'pool',
            'sessionTime',
            'rundown',
        ])->findOrFail($drawingId);

        $court = $drawing->court;

        if (! $court) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Lapangan Belum Diatur',
                'text' => 'Drawing ini belum memiliki lapangan yang ditentukan.',
            ]);

            return;
        }

        $matchNumber = $drawing->matchNumber;
        $draftType = $drawing->draft_type ?? $matchNumber?->draft_type ?? 'embu';

        // Update state di MatchNumber sesuai jenis pertandingan
        if ($draftType === 'embu') {
            $matchNumber?->update(['active_registration_id' => $drawing->registration_id]);
        } else {
            $matchNumber?->update(['active_bracket_node' => '0_0']);
        }

        // Simpan drawing aktif ke court agar monitor wasit dapat menampilkan
        // konteks lengkap: pool, sesi, rundown, kontingen, draft_type, dll.
        $court->update([
            'active_match_id' => $drawing->match_number_id,
            'active_drawing_id' => $drawing->id,
            'active_registration_id' => $drawing->registration_id,
            'active_bracket_node' => $draftType !== 'embu' ? '0_0' : null,
        ]);

        $contingentName = $drawing->registration?->contingent?->name ?? '—';
        $poolLabel = $drawing->pool ? 'Pool '.$drawing->pool->name : null;
        $sessionLabel = $drawing->sessionTime?->name;

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Pertandingan Aktif!',
            'text' => implode(' · ', array_filter([
                $matchNumber?->name ?? '—',
                $contingentName,
                $poolLabel,
                $sessionLabel,
                '→ '.$court->name,
            ])),
        ]);
    }

    public function clearCourt(int $courtId): void
    {
        $court = Court::findOrFail($courtId);
        $court->update([
            'active_match_id' => null,
            'active_registration_id' => null,
            'active_bracket_node' => null,
            'active_drawing_id' => null,
        ]);

        // Clear timer cache for this court
        Cache::forget("court_{$courtId}_timer");

        $this->dispatch('swal', [
            'icon' => 'info',
            'title' => 'Lapangan Dibersihkan',
            'text' => $court->name.' sekarang idle / kosong.',
        ]);
    }

    public function clearAllCourts(): void
    {
        $allCourts = Court::all();

        // Reset all courts
        foreach ($allCourts as $court) {
            $court->update([
                'active_match_id' => null,
                'active_registration_id' => null,
                'active_bracket_node' => null,
                'active_drawing_id' => null,
            ]);

            // Clear timer cache for each court
            Cache::forget("court_{$court->id}_timer");
        }

        // Reset all match numbers active states
        MatchNumber::query()->update([
            'active_registration_id' => null,
            'active_bracket_node' => null,
        ]);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Semua Lapangan & Match Di-reset',
            'text' => 'Seluruh status aktif telah dibersihkan secara serentak.',
        ]);
    }

    public function openRefereeModal($courtId, $rundownId = null, $sessionId = null)
    {
        $this->assigningCourtId = $courtId;
        $this->assigningRundownId = $rundownId ? (string) $rundownId : null;
        $this->assigningSessionId = $sessionId ? (string) $sessionId : null;
        $this->showRefereeModal = true;
        $this->searchReferee = '';

        $this->loadExistingReferees();
    }

    public function updatedAssigningRundownId()
    {
        $this->loadExistingReferees();
    }

    public function updatedAssigningSessionId()
    {
        $this->loadExistingReferees();
    }

    public function syncFromSchedule()
    {
        $this->loadExistingReferees();

        if (count($this->selectedReferees) === 5) {
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Data Ditemukan',
                'text' => '5 wasit telah ditarik dari jadwal.',
            ]);
        } else {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Data Tidak Lengkap',
                'text' => 'Hanya ditemukan '.count($this->selectedReferees).' wasit di jadwal untuk sesi ini.',
            ]);
        }
    }

    protected function loadExistingReferees()
    {
        if ($this->assigningRundownId && $this->assigningSessionId && $this->assigningCourtId) {
            $this->selectedReferees = ScheduleReferee::where('court_id', (int) $this->assigningCourtId)
                ->where('rundown_id', (int) $this->assigningRundownId)
                ->where('session_time_id', (int) $this->assigningSessionId)
                ->where('judge_index', '>', 0)
                ->orderBy('judge_index')
                ->pluck('referee_id')
                ->map(fn ($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedReferees = [];
        }
    }

    public function saveRefereeAssignment()
    {
        $this->validate([
            'assigningRundownId' => 'required',
            'assigningSessionId' => 'required',
            'selectedReferees' => 'required|array|min:5|max:5',
        ], [
            'selectedReferees.min' => 'Wajib memilih tepat 5 wasit.',
            'selectedReferees.max' => 'Wajib memilih tepat 5 wasit.',
        ]);

        DB::beginTransaction();
        try {
            // Clear old
            ScheduleReferee::where('rundown_id', $this->assigningRundownId)
                ->where('session_time_id', $this->assigningSessionId)
                ->where('court_id', $this->assigningCourtId)
                ->where('judge_index', '>', 0)
                ->delete();

            // Save new to Schedule
            foreach ($this->selectedReferees as $index => $refereeId) {
                ScheduleReferee::create([
                    'rundown_id' => $this->assigningRundownId,
                    'session_time_id' => $this->assigningSessionId,
                    'court_id' => $this->assigningCourtId,
                    'referee_id' => $refereeId,
                    'judge_index' => $index + 1,
                ]);
            }

            // Sync to Active Court Referees (Live Display)
            ActiveCourtReferee::where('court_id', $this->assigningCourtId)->delete();
            foreach ($this->selectedReferees as $index => $refereeId) {
                ActiveCourtReferee::create([
                    'court_id' => $this->assigningCourtId,
                    'referee_id' => $refereeId,
                    'judge_index' => $index + 1,
                ]);
            }

            DB::commit();

            $this->showRefereeModal = false;
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Penugasan Berhasil',
                'text' => 'Panel wasit telah diperbarui.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal Menyimpan',
                'text' => $e->getMessage(),
            ]);
        }
    }

    public function resetActiveReferees($courtId)
    {
        ActiveCourtReferee::where('court_id', $courtId)->delete();

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Panel Dikosongkan',
            'text' => 'Seluruh wasit aktif untuk lapangan ini telah dihapus.',
        ]);
    }

    public function resetCourtReferees()
    {
        if (! $this->assigningCourtId || ! $this->assigningRundownId || ! $this->assigningSessionId) {
            return;
        }

        ScheduleReferee::where('court_id', $this->assigningCourtId)
            ->where('rundown_id', $this->assigningRundownId)
            ->where('session_time_id', $this->assigningSessionId)
            ->where('judge_index', '>', 0)
            ->delete();

        // Also clear active display
        ActiveCourtReferee::where('court_id', $this->assigningCourtId)->delete();

        $this->selectedReferees = [];
        $this->showRefereeModal = false;

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Penugasan Dihapus',
            'text' => 'Seluruh wasit untuk sesi ini telah dikosongkan.',
        ]);
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->filterCourt = '';
        $this->filterSession = '';
        $this->filterRundown = '';
        $this->filterPool = '';
        $this->filterRound = '';
        $this->filterType = '';
        $this->filterContingent = '';
        $this->filterAgeGroup = '';
        $this->filterMatchNumber = '';
        $this->filterGender = '';

        $this->resetPage();

        $this->dispatch('swal', [
            'icon' => 'info',
            'title' => 'Filter Direset',
            'text' => 'Semua pencarian dan filter telah dikosongkan.',
        ]);
    }

    public function render()
    {
        $courts = Court::with([
            'activeMatch',
            'activeDrawing.pool',
            'activeDrawing.sessionTime',
            'activeDrawing.rundown',
            'activeDrawing.registration.contingent',
        ])->orderBy('order')->get();

        $now = now();
        $currentTimeSession = SessionTime::whereTime('start_time', '<=', $now)
            ->whereTime('end_time', '>=', $now)
            ->first();
        $currentDateRundown = Rundown::where('date', $now->toDateString())->first();

        // Attach current referees for each court's context (Live Display)
        foreach ($courts as $court) {
            $court->current_referees = ActiveCourtReferee::with('referee.user')
                ->where('court_id', $court->id)
                ->orderBy('judge_index')
                ->get();
        }

        $sessions = SessionTime::orderBy('start_time')->get();
        $rundowns = Rundown::orderBy('date')->get();
        $pools = Pool::orderBy('order')->get();
        $contingents = Contingent::orderBy('name')->get();

        $rounds = DrawingMatchNumber::whereNotNull('round')
            ->distinct()
            ->orderBy('round')
            ->pluck('round');

        $ageGroups = AgeGroup::orderBy('order')->get();

        $matchNumberQuery = MatchNumber::orderBy('name');
        if ($this->filterAgeGroup) {
            $matchNumberQuery->where('age_group_id', $this->filterAgeGroup);
        }
        if ($this->filterGender) {
            $matchNumberQuery->where('gender', $this->filterGender);
        }
        if ($this->filterType) {
            $matchNumberQuery->where('draft_type', $this->filterType);
        }
        $matchNumbers = $matchNumberQuery->get();

        // ── Core query: grouped per scheduled match session ─────────
        $query = DrawingMatchNumber::with([
            'matchNumber.ageGroup',
            'pool',
            'court',
            'sessionTime',
            'rundown',
        ])->whereNotNull('match_number_id')
            ->select(
                'match_number_id',
                'court_id',
                'pool_id',
                'session_time_id',
                'rundown_id',
                'round',
                'draft_type'
            )
            ->selectRaw('MIN(id) as id, COUNT(registration_id) as total_athletes, MIN(sequence_number) as sequence_number')
            ->groupBy(
                'match_number_id',
                'court_id',
                'pool_id',
                'session_time_id',
                'rundown_id',
                'round',
                'draft_type'
            );

        // ── Filters ────────────────────────────────────────────────────────────
        if (! empty($this->filterCourt)) {
            $query->where('court_id', $this->filterCourt);
        }
        if (! empty($this->filterSession)) {
            $query->where('session_time_id', $this->filterSession);
        }
        if (! empty($this->filterRundown)) {
            $query->where('rundown_id', $this->filterRundown);
        }
        if (! empty($this->filterPool)) {
            $query->where('pool_id', $this->filterPool);
        }
        if (! empty($this->filterRound)) {
            $query->where('round', $this->filterRound);
        }
        if (! empty($this->filterType)) {
            $query->where('draft_type', $this->filterType);
        }
        if (! empty($this->filterAgeGroup)) {
            $query->whereHas('matchNumber', function ($q) {
                $q->where('age_group_id', $this->filterAgeGroup);
            });
        }
        if (! empty($this->filterMatchNumber)) {
            $query->where('match_number_id', $this->filterMatchNumber);
        }
        if (! empty($this->filterGender)) {
            $query->whereHas('matchNumber', function ($q) {
                $q->where('gender', $this->filterGender);
            });
        }

        // Filter by contingent via registration relationship
        if (! empty($this->filterContingent)) {
            $query->whereHas('registration.contingent', function ($q) {
                $q->where('contingents.id', $this->filterContingent);
            });
        }

        // Search by match number name or contingent name
        if (! empty($this->search)) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('matchNumber', fn ($mq) => $mq->where('name', 'ilike', '%'.$search.'%'))
                    ->orWhereHas('registration.contingent', fn ($cq) => $cq->where('name', 'ilike', '%'.$search.'%'));
            });
        }

        $query->orderBy('rundown_id')->orderBy('session_time_id')->orderByRaw('MIN(sequence_number)');

        $routePrefix = request()->is('*panitera*') ? 'admin.panitera.scoring' : 'admin.arbitrase.scoring';

        $refereesQuery = Referee::with('user');
        if (! empty($this->searchReferee)) {
            $refereesQuery->whereHas('user', function ($q) {
                $q->where('name', 'ilike', '%'.$this->searchReferee.'%');
            })->orWhere('license_number', 'ilike', '%'.$this->searchReferee.'%')
                ->orWhere('certification_level', 'ilike', '%'.$this->searchReferee.'%');
        }
        $allReferees = $refereesQuery->get()->sortBy([
            ['certification_level', 'asc'],
        ]);

        return view('livewire.admin.arbitrase.scoring.admin-arbitrase-scoring-index', [
            'drawings' => $query->paginate(20),
            'courts' => $courts,
            'sessions' => $sessions,
            'rundowns' => $rundowns,
            'pools' => $pools,
            'contingents' => $contingents,
            'rounds' => $rounds,
            'ageGroups' => $ageGroups,
            'matchNumbers' => $matchNumbers,
            'routePrefix' => $routePrefix,
            'allReferees' => $allReferees,
        ]);
    }
}
