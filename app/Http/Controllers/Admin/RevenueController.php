<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class RevenueController extends Controller
{
    public function index(): View
    {
        return view('admin.revenue');
    }
}
