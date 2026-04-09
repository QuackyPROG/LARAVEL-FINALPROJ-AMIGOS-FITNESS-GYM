<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassSchedule extends Model
{
    protected $fillable = [
        'name',
        'coach_id',
        'day_of_week',
        'time',
        'capacity',
        'is_recurring',
    ];

    protected function casts(): array
    {
        return [
            'is_recurring' => 'boolean',
        ];
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }
}
