<?php

namespace App\Http\Controllers;

class QcDashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.qc');
    }
}
