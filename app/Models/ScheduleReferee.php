<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleReferee extends Model
{
    use HasFactory;

    protected $fillable = [
        'rundown_id',
        'session_time_id',
        'court_id',
        'referee_id',
        'judge_index',
    ];

    public function rundown()
    {
        return $this->belongsTo(Rundown\Rundown::class);
    }

    public function sessionTime()
    {
        return $this->belongsTo(SessionTime::class);
    }

    public function court()
    {
        return $this->belongsTo(Court\Court::class);
    }

    public function referee()
    {
        return $this->belongsTo(Referee::class);
    }
}
