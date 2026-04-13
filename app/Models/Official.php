<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Official extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        // Identity data here in the future
    ];

    public function registrations()
    {
        return $this->belongsToMany(Registration::class, 'registration_official')
            ->withPivot(['role'])
            ->withTimestamps();
    }

    public function latestRegistration()
    {
        return $this->registrations()->latest('registrations.created_at')->first();
    }

    public function getRoleAttribute()
    {
        return $this->latestRegistration()?->pivot?->role;
    }

    public function getContingentAttribute()
    {
        return $this->latestRegistration()?->contingent;
    }
}
