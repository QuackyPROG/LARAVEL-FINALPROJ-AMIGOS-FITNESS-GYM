<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CoachController extends Controller
{
    public function index(): View
    {
        return view('admin.coaches.index');
    }

    public function announcements(): View
    {
        return view('admin.announcements.index');
    }
}
