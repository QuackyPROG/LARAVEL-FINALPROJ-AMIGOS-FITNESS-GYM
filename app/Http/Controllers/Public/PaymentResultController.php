<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\SiteContent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentResultController extends Controller
{
    public function success(Request $request): View
    {
        $ref = $request->query('ref');
        $membership = $ref ? Membership::with(['user', 'plan'])->find($ref) : null;

        $isPending = $membership && $membership->status === 'pending_payment';

        return view('public.payment-success', compact('membership', 'isPending'));
    }

    public function failed(Request $request): View
    {
        $ref = $request->query('ref');
        $membership = $ref ? Membership::with('plan')->find($ref) : null;
        $gymPhone = SiteContent::get('gym_phone');

        return view('public.payment-failed', compact('membership', 'gymPhone'));
    }
}
