<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class MemberCardService
{
    public function generateToken(User $user): string
    {
        $payload = [
            'member_id' => $user->id,
            'expires_at' => $user->activeMembership?->expires_at?->toISOString(),
            'iat' => time(),
            'exp' => time() + 86400,
        ];

        $token = JWT::encode($payload, config('app.key'), 'HS256');

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['member_card_token' => $token],
        );

        return $token;
    }

    public function verifyToken(string $token): array
    {
        $decoded = JWT::decode($token, new Key(config('app.key'), 'HS256'));

        return (array) $decoded;
    }
}
