<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function enrolledBookings(): HasMany
    {
        return $this->hasMany(Booking::class)->whereNotIn('status', ['cancelled']);
    }
}
