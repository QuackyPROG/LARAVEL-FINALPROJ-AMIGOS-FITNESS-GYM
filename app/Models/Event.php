<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'cover_image',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
            'is_visible' => 'boolean',
        ];
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true)->where('date', '>=', now());
    }
}
