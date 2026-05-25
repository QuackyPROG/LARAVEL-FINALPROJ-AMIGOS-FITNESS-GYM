<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SalesSummaryController extends Controller
{
    public function index(): View
    {
        return view('admin.sales-summary');
    }
}
