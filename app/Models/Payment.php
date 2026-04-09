<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasUlids;

    protected $fillable = [
        'user_id',
        'type',
        'status',
        'amount',
        'currency',
        'paymongo_id',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'amount' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
