<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\MembershipPlan;
use App\Models\SiteContent;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $plans = MembershipPlan::active()->orderBy('price')->get();

        if ($plans->isEmpty()) {
            $plans = MembershipPlan::orderBy('price')->get();
        }

        $coaches = Coach::orderBy('name')->get();

        $content = [
            'hero_title' => SiteContent::get('hero_title', 'Train Hard. Live Strong.'),
            'hero_subtitle' => SiteContent::get('hero_subtitle', 'Join AmigosFitnessGym — the community-driven gym built for those who are serious about their fitness journey.'),
            'hero_image' => SiteContent::get('hero_image', ''),
            'gym_hours' => SiteContent::get('gym_hours', 'Mon–Fri: 5:00 AM – 10:00 PM | Sat–Sun: 6:00 AM – 8:00 PM'),
            'gym_address' => SiteContent::get('gym_address', '123 Fitness Street, Makati City, Metro Manila'),
            'gym_phone' => SiteContent::get('gym_phone', '+63 900 000 0000'),
        ];

        return view('public.home', compact('plans', 'coaches', 'content'));
    }
}
