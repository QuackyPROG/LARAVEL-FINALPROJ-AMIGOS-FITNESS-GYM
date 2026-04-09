<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        if (auth()->check()) {
            $destination = auth()->user()->role === 'admin'
                ? route('admin.dashboard')
                : route('portal.dashboard');

            return redirect($destination);
        }

        $selectedPlanId = $request->query('plan');

        return view('public.register', compact('selectedPlanId'));
    }
}
