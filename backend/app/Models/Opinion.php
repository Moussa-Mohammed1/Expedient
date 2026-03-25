<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Opinion extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'author_id',
        'rate',
        'content',
        'isAllowed',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'isAllowed' => 'boolean',
    ];

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
