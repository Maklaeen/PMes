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

    public function users() { return view('admin.users.index'); }
    public function products() { return view('admin.products.index'); }
    public function materials() { return view('admin.materials.index'); }
    public function bom() { return view('admin.bom.index'); }
    public function productionSchedule() { return view('admin.production.schedule'); }
    public function workOrders() { return view('admin.production.work_orders'); }
    public function productionCosting() { return view('admin.production.costing'); }
    public function qualityControl() { return view('admin.production.quality'); }
    public function auditLogs() { return view('admin.system.audit_logs'); }
}
