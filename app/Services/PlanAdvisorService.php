<?php

namespace App\Services;

use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlanAdvisorService
{
    public function recommend(string $email): ?array
    {
        $plans = MembershipPlan::active()->orderByDesc('is_daily')->orderBy('price')->get();
        if ($plans->isEmpty()) {
            return null;
        }

        $now = Carbon::now();
        $weekStart = $now->copy()->startOfWeek();

        $weeklyDailyCount = Membership::whereHas('plan', fn ($q) => $q->where('is_daily', true))
            ->where('starts_at', '>=', $weekStart->toDateString())
            ->count();

        $weeklyMonthlyCount = Membership::whereHas('plan', fn ($q) => $q->where('is_daily', false))
            ->where('starts_at', '>=', $weekStart->toDateString())
            ->count();

        $existingUser = User::where('email', $email)->with('memberships.plan')->first();
        $priorPlans = $existingUser
            ? $existingUser->memberships->pluck('plan.name')->filter()->unique()->values()->toArray()
            : [];

        $planList = $plans->map(fn ($p) => "ID:{$p->id} Name:{$p->name} Price:{$p->price} Days:{$p->duration_days} IsDaily:".($p->is_daily ? 'true' : 'false'))->join(', ');

        $prompt = "You are a fitness gym plan advisor. Based on the context, recommend the best membership plan for a new walk-in client.\n\n"
            . "Current time: {$now->format('l H:i')}\n"
            . "This week: {$weeklyDailyCount} daily passes, {$weeklyMonthlyCount} monthly memberships activated\n"
            . "Email: {$email}\n"
            . ($priorPlans ? 'Prior plans: '.implode(', ', $priorPlans)."\n" : "New client — no prior membership history\n")
            . "Available plans: {$planList}\n\n"
            . "Respond with ONLY valid JSON: {\"plan_id\": <integer>, \"rationale\": \"<max 120 chars>\"}\n"
            . "No markdown, no explanation, only the JSON object.";

        try {
            $response = Http::withHeaders([
                'x-api-key' => config('services.anthropic.key'),
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->timeout(10)->post('https://api.anthropic.com/v1/messages', [
                'model' => config('services.anthropic.model', 'claude-sonnet-4-6'),
                'max_tokens' => 128,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            if (! $response->successful()) {
                return null;
            }

            $text = data_get($response->json(), 'content.0.text', '');
            $parsed = json_decode($text, true);

            if (! is_array($parsed) || ! isset($parsed['plan_id'], $parsed['rationale'])) {
                return null;
            }

            $planId = (int) $parsed['plan_id'];
            if (! $plans->contains('id', $planId)) {
                return null;
            }

            return [
                'plan_id' => $planId,
                'rationale' => substr((string) $parsed['rationale'], 0, 120),
            ];
        } catch (\Throwable $e) {
            Log::debug('PlanAdvisorService failed: '.$e->getMessage());

            return null;
        }
    }
}
