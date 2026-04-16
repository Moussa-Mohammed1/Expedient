<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'salle_id',
        'content',
    ];

    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class);
    }
}
