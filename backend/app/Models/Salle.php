<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tagline',
        'sessionType',
        'description',
        'existenceYears',
        'city',
        'background',
        'logo',
        'coach_id',
        'sport_id',
    ];

    protected $attributes = [
        'background' => 'assets/images/salle_default.jpeg',
    ];

    protected $casts = [
        'existenceYears' => 'integer',
    ];

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    public function horaires(): HasMany
    {
        return $this->hasMany(Horaire::class);
    }

    public function salleEquipments(): HasMany
    {
        return $this->hasMany(SalleEquipment::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'salle_service')
            ->withTimestamps();
    }

    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'salle_equipment')
            ->withPivot(['condition', 'description'])
            ->withTimestamps();
    }

    public function scopeSearchWords(Builder $query, array $words): Builder
    {
        $words = array_values(array_filter($words, fn($word) => $word !== ''));

        return $query->when($words !== [], fn(Builder $q) => $q->where(
            fn(Builder $group) => collect($words)->each(
                fn(string $word) => $group
                    ->orWhereRaw('LOWER(name) LIKE ?', ["%{$word}%"])
                    ->orWhereRaw('LOWER(city) LIKE ?', ["%{$word}%"])
            )
        ));
    }

    public function isFavoris(): bool
    {
        if (! auth()->check()) {
            return false;
        }

        $user = auth()->user();

        if ($user->relationLoaded('favoriteSalles')) {
            return $user->favoriteSalles->contains($this->getKey());
        }

        return $user->favoriteSalles()
            ->where('salles.id', $this->getKey())
            ->exists();
    }
}
