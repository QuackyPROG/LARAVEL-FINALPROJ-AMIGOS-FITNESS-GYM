<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberProfile extends Model
{
    protected $fillable = [
        'user_id',
        'government_id_path',
        'member_card_token',
        'id_type',
        'id_number',
    ];

    /** @var string[] */
    public const ID_TYPES = [
        'national',
        'passport',
        'drivers_license',
        'sss',
        'philhealth',
        'pagibig',
    ];

    public static function validationRuleForType(string $type): string
    {
        return match ($type) {
            'national' => 'regex:/^\d{4}-\d{4}-\d{4}$/',
            'passport' => 'regex:/^[A-Z]\d{7}[A-Z]$/',
            'drivers_license' => 'regex:/^[A-Z]\d{2}-\d{2}-\d{6}$/',
            'sss' => 'regex:/^\d{2}-\d{7}-\d{1}$/',
            'philhealth' => 'regex:/^\d{12}$/',
            'pagibig' => 'regex:/^\d{12}$/',
            default => 'string',
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
