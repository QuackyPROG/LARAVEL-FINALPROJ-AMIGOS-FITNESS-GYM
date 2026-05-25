<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MemberController extends Controller
{
    public function index(): View
    {
        return view('admin.members.index');
    }

    public function create(): View
    {
        $plans = MembershipPlan::active()->orderBy('price')->get();

        return view('admin.members.create', compact('plans'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'plan_id' => 'required|exists:membership_plans,id',
        ]);

        $plan = MembershipPlan::findOrFail($data['plan_id']);
        $tempPassword = Str::random(12);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($tempPassword),
            'role' => 'member',
            'status' => 'active',
            'must_change_password' => true,
            'email_verified_at' => now(),
        ]);

        $membership = Membership::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'starts_at' => now()->toDateString(),
            'expires_at' => now()->addDays($plan->duration_days)->toDateString(),
            'status' => 'active',
            'payment_ref' => 'walk-in-'.now()->format('YmdHis'),
        ]);

        app(AuditLogger::class)->log('member.created_walk_in', $user, [
            'plan' => $plan->name,
            'temp_password_set' => true,
        ]);

        return redirect()->route('admin.members.show', $user)
            ->with('success', "Walk-in member {$user->name} created. Temp password: {$tempPassword}");
    }

    public function show(User $member): View
    {
        return view('admin.members.show', compact('member'));
    }

    public function govId(User $member): StreamedResponse
    {
        abort_unless(request()->hasValidSignature(), 403);

        $path = $member->profile?->government_id_path;

        abort_if(! $path || ! Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path);
    }

    public function export(Request $request): StreamedResponse
    {
        $search = $request->query('search', '');
        $statusFilter = $request->query('statusFilter', '');

        $query = User::where('role', 'member')
            ->with(['activeMembership.plan'])
            ->when($search, function ($q) use ($search) {
                $term = '%'.strtolower($search).'%';

                return $q->where(function ($q2) use ($term): void {
                    $q2->whereRaw('LOWER(name) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$term]);
                });
            })
            ->when($statusFilter, fn ($q) => $q->where('status', $statusFilter))
            ->latest()
            ->get();

        $filename = 'members-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($query): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Email', 'Phone', 'Date of Birth', 'Status', 'Plan', 'Starts At', 'Expires At']);
            foreach ($query as $member) {
                $membership = $member->activeMembership;
                fputcsv($handle, [
                    $member->name,
                    $member->email,
                    $member->phone ?? '',
                    $member->dob?->format('Y-m-d') ?? '',
                    $member->status,
                    $membership?->plan?->name ?? '',
                    $membership?->starts_at?->format('Y-m-d') ?? '',
                    $membership?->expires_at?->format('Y-m-d') ?? '',
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=utf-8']);
    }
}
