<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MyMembershipController extends Controller
{
    public function index(): View
    {
        $user = auth()->user()->load(['memberships.plan', 'consents.snapshot']);

        return view('portal.my-membership', compact('user'));
    }
}
