<?php

namespace App\Models\MatchNumber;

use App\Models\Athlete;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\Group\AgeGroup;
use App\Models\RandoriMatchResult;
use App\Models\Referee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age_group_id',
        'gender',
        'draft_type',
        'max_athletes',
        'order',
        'drawing_data',
        'drawing_generated_at',
        'is_active',
        'active_bracket_node',
        'active_registration_id',
    ];

    protected $casts = [
        'drawing_data' => 'array',
        'drawing_generated_at' => 'datetime',
        'is_active' => 'boolean',
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

    public function referees()
    {
        return $this->belongsToMany(Referee::class, 'match_number_referee')
            ->withPivot('judge_index')
            ->withTimestamps();
    }

    public function embuScores()
    {
        return $this->hasMany(EmbuScore::class);
    }

    public function randoriResults()
    {
        return $this->hasMany(RandoriMatchResult::class);
    }

    public function drawings()
    {
        return $this->hasMany(DrawingMatchNumber::class);
    }

    public function getGenderIndoAttribute(): string
    {
        return match ($this->gender) {
            'Male' => 'Laki-laki',
            'Female' => 'Perempuan',
            default => '-',
        };
    }
}
