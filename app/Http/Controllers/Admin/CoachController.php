<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use Illuminate\View\View;

class CoachController extends Controller
{
    public function index(): View
    {
        return view('admin.coaches.index');
    }

    public function show(Coach $coach): View
    {
        return view('admin.coaches.show', compact('coach'));
    }

    public function announcements(): View
    {
        return view('admin.announcements.index');
    }
}
