<?php

namespace App\Models;

use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MatchNumberMerge extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'age_group_id', 'type'];

    public function ageGroup()
    {
        return $this->belongsTo(AgeGroup::class);
    }

    public function details()
    {
        return $this->hasMany(MatchNumberMergeDetail::class, 'match_number_merge_id');
    }

    public function matchNumbers()
    {
        return $this->belongsToMany(MatchNumber::class, 'match_number_merge_details', 'match_number_merge_id', 'match_number_id');
    }

    public function getContingents()
    {
        $matchNumberIds = $this->matchNumbers->pluck('id');

        return DB::table('athlete_match_number')
            ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
            ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
            ->whereIn('athlete_match_number.match_number_id', $matchNumberIds)
            ->select('contingents.name', 'contingents.id')
            ->distinct()
            ->get();
    }
}
