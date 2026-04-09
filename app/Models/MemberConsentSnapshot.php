<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberConsentSnapshot extends Model
{
    protected $fillable = [
        'consent_id',
        'body',
    ];

    public function consent(): BelongsTo
    {
        return $this->belongsTo(MemberConsent::class, 'consent_id');
    }
}
