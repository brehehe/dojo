<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AthleteContingentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'athlete_id',
        'contingent_id',
        'moved_at',
        'notes',
    ];

    protected $casts = [
        'moved_at' => 'datetime',
    ];

    public function athlete()
    {
        return $this->belongsTo(Athlete::class);
    }

    public function contingent()
    {
        return $this->belongsTo(Contingent::class);
    }
}
