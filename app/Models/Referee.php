<?php

namespace App\Models;

use Database\Factories\RefereeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'nik',
    'phone',
    'gender',
    'birth_place',
    'birth_date',
    'address',
    'certification_level',
    'license_number',
    'province',
    'city',
    'photo',
])]
class Referee extends Model
{
    /** @use HasFactory<RefereeFactory> */
    use HasFactory;

    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Get the user that owns the referee profile
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(ScheduleReferee::class);
    }

    /**
     * Get the referee's name from the user relationship.
     */
    public function getNameAttribute(): string
    {
        return $this->user->name ?? '-';
    }

    /**
     * Get observations submitted by contingents for this referee.
     */
    public function observations(): HasMany
    {
        return $this->hasMany(RefereeObservation::class);
    }
}
