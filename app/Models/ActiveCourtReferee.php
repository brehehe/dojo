<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveCourtReferee extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'court_id',
        'referee_id',
        'judge_index',
    ];

    public function court()
    {
        return $this->belongsTo(Court\Court::class);
    }

    public function referee()
    {
        return $this->belongsTo(Referee::class);
    }
}
