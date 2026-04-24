<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// new added imports
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\PlannerDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/profile', [AdminProfileController::class, 'show'])->name('admin.profile');
    Route::patch('/admin/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::patch('/admin/profile/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.password');
    Route::post('/admin/profile/verify-email', [AdminProfileController::class, 'sendVerification'])->name('admin.profile.verify');

    // Management Routes
    Route::get('/admin/users', [AdminDashboardController::class, 'users'])->name('admin.users');
    Route::get('/admin/products', [AdminDashboardController::class, 'products'])->name('admin.products');
    Route::get('/admin/materials', [AdminDashboardController::class, 'materials'])->name('admin.materials');
    Route::get('/admin/bom', [AdminDashboardController::class, 'bom'])->name('admin.bom');
    Route::get('/admin/production-schedule', [AdminDashboardController::class, 'productionSchedule'])->name('admin.production_schedule');
    Route::get('/admin/work-orders', [AdminDashboardController::class, 'workOrders'])->name('admin.work_orders');
    Route::get('/admin/production-costing', [AdminDashboardController::class, 'productionCosting'])->name('admin.production_costing');
    Route::get('/admin/quality-control', [AdminDashboardController::class, 'qualityControl'])->name('admin.quality_control');
    Route::get('/admin/audit-logs', [AdminDashboardController::class, 'auditLogs'])->name('admin.audit_logs');
});

Route::middleware(['auth', 'is_planner'])->group(function () {
    Route::get('/planner/dashboard', [PlannerDashboardController::class, 'index'])->name('planner.dashboard');
});

require __DIR__.'/auth.php';
