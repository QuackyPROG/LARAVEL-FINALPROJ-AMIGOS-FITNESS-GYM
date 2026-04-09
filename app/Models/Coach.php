<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coach extends Model
{
    protected $fillable = [
        'name',
        'photo',
        'bio',
        'specializations',
    ];

    protected function casts(): array
    {
        return [
            'specializations' => 'array',
        ];
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(CoachAvailability::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class);
    }
}
