<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SiteContentController extends Controller
{
    public function index(): View
    {
        return view('admin.site-content');
    }
}
