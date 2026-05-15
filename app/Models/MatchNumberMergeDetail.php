<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchNumberMergeDetail extends Model
{
    use HasFactory;

    protected $fillable = ['match_number_merge_id', 'match_number_id'];

    public function merge()
    {
        return $this->belongsTo(MatchNumberMerge::class, 'match_number_merge_id');
    }

    public function matchNumber()
    {
        return $this->belongsTo(MatchNumber::class);
    }
}
