<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Official extends Model
{
    use HasFactory;

    protected $fillable = [
        'contingent_id',
        'name',
        'role',
        'phone',
    ];

    public function contingent()
    {
        return $this->belongsTo(Contingent::class);
    }
}
