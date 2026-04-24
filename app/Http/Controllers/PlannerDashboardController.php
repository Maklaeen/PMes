<?php

namespace App\Http\Controllers;

class PlannerDashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.planner');
    }
}
