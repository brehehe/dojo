<?php

namespace App\Models\MatchNumber;

use App\Models\Athlete;
use App\Models\Group\AgeGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchNumber extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'age_group_id',
        'gender',
        'draft_type',
        'max_athletes',
        'order',
    ];

    public function ageGroup()
    {
        return $this->belongsTo(AgeGroup::class);
    }

    public function athletes()
    {
        return $this->belongsToMany(Athlete::class, 'athlete_match_number')
            ->withPivot('registration_id')
            ->withTimestamps();
    }
}
