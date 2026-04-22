<?php

namespace App\Models;

use App\Models\AthleteContingentHistory;
use App\Models\MatchNumber\MatchNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Athlete extends Model
{
    use HasFactory;

    protected $casts = [
        'birth_date' => 'date',
        'achievement_history' => 'array',
    ];

    protected $fillable = [
        'nik',
        'name',
        'gender',
        'birth_place',
        'blood_type',
        'birth_date',
        'address',
        'phone',
        'photo_path',
        'bpjs_number',
        'bpjs_status',
        'bpjs_card_path',
        'identity_card_path',
        'identity_document_path',
        'achievement_history',
    ];

    public function registrations()
    {
        return $this->belongsToMany(Registration::class, 'registration_athlete')
            ->withPivot(['weight', 'kyu', 'age_group', 'rank', 'match_type', 'dojo_origin', 'city', 'age'])
            ->withTimestamps();
    }

    public function latestRegistration()
    {
        return $this->registrations()->latest('registrations.created_at')->first();
    }

    public function getWeightAttribute()
    {
        return $this->latestRegistration()?->pivot?->weight;
    }

    public function getKyuAttribute()
    {
        return $this->latestRegistration()?->pivot?->kyu;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function matchNumbers()
    {
        return $this->belongsToMany(MatchNumber::class, 'athlete_match_number')
            ->withPivot('registration_id', 'technique_ids')
            ->withTimestamps();
    }

    public function contingents()
    {
        return $this->belongsToMany(Contingent::class, 'athlete_contingent')
            ->withPivot('is_primary', 'joined_at')
            ->withTimestamps();
    }

    public function getContingentAttribute()
    {
        return $this->contingents()->wherePivot('is_primary', true)->first() 
            ?? $this->latestRegistration()?->contingent;
    }

    public function contingentHistories()   
    {
        return $this->hasMany(AthleteContingentHistory::class)->orderBy('moved_at', 'desc');
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
