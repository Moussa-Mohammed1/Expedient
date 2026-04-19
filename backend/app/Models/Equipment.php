<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipments';

    protected $fillable = [
        'name',
        'category',
        'image',
    ];

    public function salles(): BelongsToMany
    {
        return $this->belongsToMany(Salle::class, 'salle_equipment')
            ->withPivot(['condition', 'description'])
            ->withTimestamps();
    }

    public function salleEquipments(): HasMany
    {
        return $this->hasMany(SalleEquipment::class);
    }
}
