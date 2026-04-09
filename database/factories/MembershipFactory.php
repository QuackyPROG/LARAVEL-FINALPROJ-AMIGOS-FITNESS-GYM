<?php

namespace Database\Factories;

use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MembershipFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'plan_id' => MembershipPlan::factory(),
            'starts_at' => now(),
            'expires_at' => now()->addDays(30),
            'status' => 'active',
            'payment_ref' => 'test-'.uniqid(),
            'expiry_warned_at' => null,
        ];
    }
}
