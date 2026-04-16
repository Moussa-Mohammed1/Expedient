<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Horaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'salle_id',
        'day',
        'openHour',
        'closeHour',
    ];

    protected $casts = [
        'openHour' => 'datetime:H:i:s',
        'closeHour' => 'datetime:H:i:s',
    ];

    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class);
    }
}
