<?php

namespace App\Livewire\Admin\SmartWasit;

use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Referee;
use App\Models\RefereeScoreDetail;
use App\Models\Registration;
use App\Traits\HasExcelExport;
use App\Traits\HasRefereeAnalysis;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.premium')]
class NewLaporanPerbabakIndex extends Component
{
    use HasExcelExport, HasRefereeAnalysis, WithPagination;

    public string $search = '';

    public string $ageGroupFilter = '';

    public string $genderFilter = '';

    public string $matchNumberFilter = '';

    public string $refereeFilter = '';

    public string $roundFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'ageGroupFilter' => ['except' => ''],
        'genderFilter' => ['except' => ''],
        'matchNumberFilter' => ['except' => ''],
        'refereeFilter' => ['except' => ''],
        'roundFilter' => ['except' => ''],
    ];

    public function render()
    {
        $ageGroups = AgeGroup::orderBy('order')->get();
        $referees = Referee::join('users', 'referees.user_id', '=', 'users.id')
            ->select('referees.*')
            ->orderBy('users.name')
            ->get();
        $matchNumbersForFilter = MatchNumber::where('draft_type', 'embu')->orderBy('name')->get();

        // 1. Get raw scores joined with round info from drawings
        $detailsQuery = RefereeScoreDetail::join('drawing_match_numbers', function ($join) {
            $join->on('referee_score_details.match_number_id', '=', 'drawing_match_numbers.match_number_id')
                ->on('referee_score_details.scorable_id', '=', 'drawing_match_numbers.registration_id')
                ->where('referee_score_details.scorable_type', '=', Registration::class);
        })
            ->where('referee_score_details.total_calculated_score', '>', 0)
            ->select('referee_score_details.*', 'drawing_match_numbers.round as round_name');

        if ($this->roundFilter) {
            $detailsQuery->where('drawing_match_numbers.round', $this->roundFilter);
        }

        $details = $detailsQuery->get();

        // Apply Filters (Simulated for this specific report structure)
        if ($this->ageGroupFilter) {
            $details = $details->filter(fn ($d) => $d->matchNumber->age_group_id == $this->ageGroupFilter);
        }
        if ($this->matchNumberFilter) {
            $details = $details->filter(fn ($d) => $d->match_number_id == $this->matchNumberFilter);
        }

        // 2. Group by Round
        $roundOrder = ['Penyisihan', 'Semifinal', 'Final'];
        $roundStats = [];
        $prevAvg = null;

        foreach ($roundOrder as $round) {
            $roundDetails = $details->where('round_name', $round);
            if ($roundDetails->count() === 0) {
                continue;
            }

            $avg = $roundDetails->avg('total_calculated_score');
            $trend = '-';
            if ($prevAvg !== null) {
                if ($avg > $prevAvg) {
                    $trend = '📈 Meningkat';
                } elseif ($avg < $prevAvg) {
                    $trend = '📉 Menurun';
                } else {
                    $trend = '↔️ Stabil';
                }
            }

            $roundStats[] = [
                'name' => $round,
                'count' => $roundDetails->count(),
                'avg' => round($avg, 2),
                'referee_count' => $roundDetails->unique('referee_id')->count(),
                'trend' => $trend,
            ];

            $prevAvg = $avg;
        }

        // Also get detailed assessments for the bottom table
        $assessmentsQuery = RefereeScoreDetail::with(['referee', 'matchNumber.ageGroup', 'matchNumber.athletes', 'scorable.contingent'])
            ->join('drawing_match_numbers', function ($join) {
                $join->on('referee_score_details.match_number_id', '=', 'drawing_match_numbers.match_number_id')
                    ->on('referee_score_details.scorable_id', '=', 'drawing_match_numbers.registration_id')
                    ->where('referee_score_details.scorable_type', '=', Registration::class);
            })
            ->where('referee_score_details.total_calculated_score', '>', 0)
            ->whereHas('matchNumber', function ($q) {
                $q->where('draft_type', 'embu');
            })
            ->select('referee_score_details.*', 'drawing_match_numbers.round as round_name');

        if ($this->roundFilter) {
            $assessmentsQuery->where('drawing_match_numbers.round', $this->roundFilter);
        }
        if ($this->ageGroupFilter) {
            $assessmentsQuery->whereHas('matchNumber', function ($q) {
                $q->where('age_group_id', $this->ageGroupFilter);
            });
        }
        if ($this->matchNumberFilter) {
            $assessmentsQuery->where('referee_score_details.match_number_id', $this->matchNumberFilter);
        }
        if ($this->refereeFilter) {
            $assessmentsQuery->where('referee_score_details.referee_id', $this->refereeFilter);
        }
        if ($this->genderFilter) {
            $assessmentsQuery->whereHas('matchNumber', function ($q) {
                $q->where('gender', $this->genderFilter);
            });
        }
        if ($this->search) {
            $assessmentsQuery->whereHas('scorable.athletes', function ($sub) {
                $sub->where('name', 'ilike', '%'.$this->search.'%');
            });
        }

        $assessments = $assessmentsQuery->latest('referee_score_details.created_at')
            ->paginate(10);

        return view('livewire.admin.smart-wasit.new-laporan-perbabak-index', [
            'ageGroups' => $ageGroups,
            'referees' => $referees,
            'matchNumbersForFilter' => $matchNumbersForFilter,
            'roundStats' => collect($roundStats),
            'assessments' => $assessments,
        ])->title('Laporan Perbabak');
    }

    public function exportExcel()
    {
        $assessmentsQuery = RefereeScoreDetail::with(['referee', 'matchNumber.ageGroup', 'matchNumber.athletes', 'scorable.contingent'])
            ->join('drawing_match_numbers', function ($join) {
                $join->on('referee_score_details.match_number_id', '=', 'drawing_match_numbers.match_number_id')
                    ->on('referee_score_details.scorable_id', '=', 'drawing_match_numbers.registration_id')
                    ->where('referee_score_details.scorable_type', '=', Registration::class);
            })
            ->where('referee_score_details.total_calculated_score', '>', 0)
            ->whereHas('matchNumber', function ($q) {
                $q->where('draft_type', 'embu');
            })
            ->select('referee_score_details.*', 'drawing_match_numbers.round as round_name');

        if ($this->roundFilter) {
            $assessmentsQuery->where('drawing_match_numbers.round', $this->roundFilter);
        }
        if ($this->ageGroupFilter) {
            $assessmentsQuery->whereHas('matchNumber', function ($q) {
                $q->where('age_group_id', $this->ageGroupFilter);
            });
        }
        if ($this->matchNumberFilter) {
            $assessmentsQuery->where('referee_score_details.match_number_id', $this->matchNumberFilter);
        }
        if ($this->refereeFilter) {
            $assessmentsQuery->where('referee_score_details.referee_id', $this->refereeFilter);
        }
        if ($this->genderFilter) {
            $assessmentsQuery->whereHas('matchNumber', function ($q) {
                $q->where('gender', $this->genderFilter);
            });
        }
        if ($this->search) {
            $assessmentsQuery->whereHas('scorable.athletes', function ($sub) {
                $sub->where('name', 'ilike', '%'.$this->search.'%');
            });
        }

        $assessments = $assessmentsQuery->latest('referee_score_details.created_at')->get();

        $exportData = [];
        foreach ($assessments as $a) {
            $athletes = $a->matchNumber->athletes->where('pivot.registration_id', $a->scorable_id)->pluck('name')->join(' & ');

            $exportData[] = [
                'Waktu' => $a->created_at->format('H:i'),
                'Babak' => $a->round_name,
                'Wasit' => $a->referee->name,
                'Nomor Pertandingan' => $a->matchNumber->name,
                'Atlet' => $athletes ?: ($a->scorable->athletes->pluck('name')->join(' & ') ?? '-'),
                'Kontingen' => $a->scorable->contingent->name ?? '-',
                'Nilai Teknik' => (float) $a->total_teknik_score,
                'Nilai Ekspresi' => (float) $a->total_ekspresi_score,
                'Total' => (float) $a->total_calculated_score,
            ];
        }

        return $this->downloadExcel(
            $exportData,
            ['Waktu', 'Babak', 'Wasit', 'Nomor Pertandingan', 'Atlet', 'Kontingen', 'Nilai Teknik', 'Nilai Ekspresi', 'Total'],
            'Laporan_Perbabak_Smart_Wasit',
            'Laporan Perbabak'
        );
    }
}
