<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MemberConsent extends Model
{
    protected $fillable = [
        'user_id',
        'document_key',
        'version',
        'ip_address',
        'method',
        'accepted_at',
    ];

    protected function casts(): array
    {
        return [
            'accepted_at' => 'datetime',
            'version' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function snapshot(): HasOne
    {
        return $this->hasOne(MemberConsentSnapshot::class, 'consent_id');
    }
}
