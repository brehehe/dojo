<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RandoriJudgeScore extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'match_number_id',
        'bracket_node',
        'judge_index',
        'waza_ari_aka',
        'ippon_aka',
        'hansoku_aka',
        'waza_ari_shiro',
        'ippon_shiro',
        'hansoku_shiro',
    ];

    public function matchNumber()
    {
        return $this->belongsTo(\App\Models\MatchNumber\MatchNumber::class);
    }
}
