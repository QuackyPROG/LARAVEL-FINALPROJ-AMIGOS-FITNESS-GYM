<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChangePasswordController extends Controller
{
    public function showForm(): View
    {
        return view('auth.change-password');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        $user->update([
            'password' => $request->password,
            'must_change_password' => false,
        ]);

        return redirect($user->isAdmin() ? '/admin/dashboard' : '/portal/dashboard')
            ->with('success', 'Password updated successfully.');
    }
}