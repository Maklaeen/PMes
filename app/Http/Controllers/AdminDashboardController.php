<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Product;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users'     => User::count(),
            'products'  => Product::count(),
            'materials' => Material::count(),
        ];

        return view('dashboard.admin', compact('stats'));
    }
}
