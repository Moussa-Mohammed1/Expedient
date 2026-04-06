<?php

namespace App\Models;

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
        'coach_id',
        'sport_id',
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
}
