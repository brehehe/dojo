<?php

namespace App\Models\Pool;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pool extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
    ];
}
