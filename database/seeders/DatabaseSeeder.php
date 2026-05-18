<?php

namespace Database\Seeders;

use App\Models\Coach;
use App\Models\MembershipPlan;
use App\Models\SiteContent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@amigosgym.com'],
            [
                'name' => 'Amigos Admin',
                'phone' => '+63 900 000 0000',
                'role' => 'admin',
                'status' => 'active',
                'must_change_password' => false,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        MembershipPlan::updateOrCreate(
            ['name' => 'Monthly'],
            [
                'duration_days' => 30,
                'price' => 999.00,
                'benefits' => ['Unlimited gym access', 'Locker room access', 'Free fitness assessment'],
                'is_active' => true,
            ]
        );

        MembershipPlan::updateOrCreate(
            ['name' => 'Quarterly'],
            [
                'duration_days' => 90,
                'price' => 2499.00,
                'benefits' => ['Unlimited gym access', 'Locker room access', 'Free fitness assessment', '1 free coach session'],
                'is_active' => true,
            ]
        );

        $contents = [
            ['key' => 'hero_title', 'value' => 'Train Hard. Live Strong.', 'type' => 'text'],
            ['key' => 'hero_subtitle', 'value' => 'Join AmigosFitnessGym — the community-driven gym built for those who are serious about their fitness journey.', 'type' => 'text'],
            ['key' => 'gym_hours', 'value' => 'Mon–Fri: 5:00 AM – 10:00 PM | Sat–Sun: 6:00 AM – 8:00 PM', 'type' => 'text'],
            ['key' => 'gym_address', 'value' => '123 Fitness Street, Makati City, Metro Manila', 'type' => 'text'],
            ['key' => 'gym_phone', 'value' => '+63 900 000 0000', 'type' => 'text'],
            ['key' => 'hero_image', 'value' => '', 'type' => 'image'],
        ];

        foreach ($contents as $content) {
            SiteContent::updateOrCreate(
                ['key' => $content['key']],
                ['value' => $content['value'], 'type' => $content['type']]
            );
        }

        $coaches = [
            [
                'name' => 'Marco Santos',
                'bio' => 'Former national weightlifting champion with 10+ years of coaching experience. Specializes in building functional strength and athletic performance.',
                'specializations' => ['Strength & Conditioning', 'Olympic Lifting', 'Powerlifting'],
                'photo' => null,
            ],
            [
                'name' => 'Ana Reyes',
                'bio' => 'Certified yoga instructor and flexibility specialist. Helps members improve mobility, reduce injury risk, and find balance in their training.',
                'specializations' => ['Yoga', 'Flexibility', 'Mobility'],
                'photo' => null,
            ],
            [
                'name' => 'Rico Cruz',
                'bio' => 'High-energy HIIT and cardio coach who transforms fitness levels fast. Known for high-intensity circuits that burn fat and build endurance.',
                'specializations' => ['HIIT', 'Cardio', 'Fat Loss'],
                'photo' => null,
            ],
        ];

        foreach ($coaches as $coach) {
            Coach::updateOrCreate(
                ['name' => $coach['name']],
                [
                    'bio' => $coach['bio'],
                    'specializations' => $coach['specializations'],
                    'photo' => $coach['photo'],
                ]
            );
        }
    }
}
