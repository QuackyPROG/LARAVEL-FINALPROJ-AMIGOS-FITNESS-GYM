<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration_days',
        'price',
        'benefits',
        'is_active',
        'is_daily',
    ];

    protected function casts(): array
    {
        return [
            'benefits' => 'array',
            'is_active' => 'boolean',
            'is_daily' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class, 'plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
