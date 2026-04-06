<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachVerification extends Model
{
    protected $fillable = [
        'coach_id',
        'status',
        'proof_document',
        'document_description',
        'rejection_cause',
        'requested_at',
        'reviewed_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }
}
