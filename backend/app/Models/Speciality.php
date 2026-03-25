<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speciality extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function coachSpecialities(): HasMany
    {
        return $this->hasMany(CoachSpeciality::class);
    }

    public function coaches(): BelongsToMany
    {
        return $this->belongsToMany(Coach::class, 'coach_specialities')
            ->withPivot(['level', 'experienceYears'])
            ->withTimestamps();
    }
}
