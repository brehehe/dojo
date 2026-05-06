<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Contingent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'leader_name',
        'leader_phone',
        'email',
        'address',
        'kab_kota',
    ];

    /**
     * Get the user that owns the contingent.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all registrations for the contingent.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function athletes()
    {
        return $this->belongsToMany(Athlete::class, 'athlete_contingent')
            ->withPivot('is_primary', 'joined_at')
            ->withTimestamps();
    }

    public function officials()
    {
        return $this->hasMany(Official::class);
    }

    public function tournamentResults(): HasManyThrough
    {
        return $this->hasManyThrough(TournamentResult::class, Registration::class);
    }
}
