<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coach extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'hasBadge',
        'reputation_rate',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'hasBadge' => 'boolean',
        'reputation_rate' => 'decimal:2',
    ];

    /**
     * The user profile associated with this coach.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The coach speciality pivot rows for this coach.
     */
    public function coachSpecialities(): HasMany
    {
        return $this->hasMany(CoachSpeciality::class);
    }

    /**
     * Specialities linked to this coach.
     */
    public function specialities(): BelongsToMany
    {
        return $this->belongsToMany(Speciality::class, 'coach_specialities')
            ->withPivot(['level', 'experienceYears'])
            ->withTimestamps();
    }
}
