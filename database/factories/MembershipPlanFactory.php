<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MembershipPlanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true).' Plan',
            'duration_days' => $this->faker->randomElement([30, 90, 180]),
            'price' => $this->faker->randomElement([999, 2499, 4499]),
            'benefits' => ['Unlimited gym access', 'Locker room access'],
            'is_active' => true,
        ];
    }
}
