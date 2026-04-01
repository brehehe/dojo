<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Athlete extends Model
{
    use HasFactory;
    protected $fillable = [
        'contingent_id',
        'nik',
        'name',
        'gender',
        'birth_date',
        'weight',
        'achievement_history',
        'kyu',
        'dojo_origin',
        'city',
        'bpjs_number',
        'bpjs_status',
        'bpjs_card_path',
        'age',
        'age_group',
        'match_type',
        'rank',
        'identity_card_path',
        'identity_document_path',
    ];

    public function contingent()
    {
        return $this->belongsTo(Contingent::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
