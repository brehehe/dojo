<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    /** @use HasFactory<\Database\Factories\RefereeFactory> */
    use HasFactory;

    /**
     * Get the user that owns the referee profile
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
