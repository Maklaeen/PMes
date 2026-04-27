<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// new added imports
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\PlannerDashboardController;
use App\Http\Controllers\InventoryDashboardController;
use App\Http\Controllers\OperatorDashboardController;
use App\Http\Controllers\QcDashboardController;
use App\Http\Controllers\ProductionScheduleController;
use App\Http\Controllers\ProductionDashboardController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\MaterialMovementController;
use App\Http\Controllers\QualityCheckController;
use App\Http\Controllers\ProductionCostController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminMaterialController;
use App\Http\Controllers\AdminBomController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

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
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::patch('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/admin/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('/admin/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/admin/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/admin/products/{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::patch('/admin/products/{product}', [AdminProductController::class, 'update'])->name('admin.products.update');

    Route::get('/admin/materials', [AdminMaterialController::class, 'index'])->name('admin.materials.index');
    Route::get('/admin/materials/create', [AdminMaterialController::class, 'create'])->name('admin.materials.create');
    Route::post('/admin/materials', [AdminMaterialController::class, 'store'])->name('admin.materials.store');
    Route::get('/admin/materials/{material}/edit', [AdminMaterialController::class, 'edit'])->name('admin.materials.edit');
    Route::patch('/admin/materials/{material}', [AdminMaterialController::class, 'update'])->name('admin.materials.update');
    Route::delete('/admin/materials/{material}', [AdminMaterialController::class, 'destroy'])->name('admin.materials.destroy');

    Route::get('/admin/bom', [AdminBomController::class, 'index'])->name('admin.bom.index');
    Route::get('/admin/bom/create', [AdminBomController::class, 'create'])->name('admin.bom.create');
    Route::post('/admin/bom', [AdminBomController::class, 'store'])->name('admin.bom.store');
    Route::get('/admin/bom/{bom}/edit', [AdminBomController::class, 'edit'])->name('admin.bom.edit');
    Route::patch('/admin/bom/{bom}', [AdminBomController::class, 'update'])->name('admin.bom.update');
    Route::delete('/admin/bom/{bom}', [AdminBomController::class, 'destroy'])->name('admin.bom.destroy');
});

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/admin/audit-logs', [AdminDashboardController::class, 'auditLogs'])->name('admin.audit_logs');
});

Route::middleware(['auth', 'is_planner'])->group(function () {
    Route::get('/planner/dashboard', [PlannerDashboardController::class, 'index'])->name('planner.dashboard');
});

Route::middleware(['auth', 'role:superadmin,admin,inventory'])->group(function () {
    Route::get('/inventory/dashboard', [InventoryDashboardController::class, 'index'])->name('inventory.dashboard');
});

Route::middleware(['auth', 'role:superadmin,admin,operator'])->group(function () {
    Route::get('/operator/dashboard', [OperatorDashboardController::class, 'index'])->name('operator.dashboard');
});

Route::middleware(['auth', 'role:superadmin,admin,qc'])->group(function () {
    Route::get('/qc/dashboard', [QcDashboardController::class, 'index'])->name('qc.dashboard');
});

// Production & Manufacturing Execution workflow
Route::middleware(['auth', 'role:superadmin,admin,planner,inventory,qc'])->prefix('production')->name('production.')->group(function () {
    Route::get('/schedules', [ProductionScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/{schedule}', [ProductionScheduleController::class, 'show'])->whereNumber('schedule')->name('schedules.show');
});

Route::middleware(['auth', 'role:superadmin,admin,planner'])->prefix('production')->name('production.')->group(function () {
    Route::get('/dashboard', [ProductionDashboardController::class, 'index'])->name('dashboard');
    Route::get('/schedules/create', [ProductionScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/schedules', [ProductionScheduleController::class, 'store'])->name('schedules.store');
    Route::post('/schedules/{schedule}/start', [ProductionScheduleController::class, 'start'])->whereNumber('schedule')->name('schedules.start');
    Route::post('/schedules/{schedule}/complete', [ProductionScheduleController::class, 'complete'])->whereNumber('schedule')->name('schedules.complete');
    Route::post('/schedules/{schedule}/generate-work-orders', [ProductionScheduleController::class, 'generateWorkOrders'])->whereNumber('schedule')->name('schedules.generate_work_orders');
});

Route::middleware(['auth', 'role:superadmin,admin'])->prefix('production')->name('production.')->group(function () {
    Route::post('/schedules/{schedule}/cancel', [ProductionScheduleController::class, 'cancel'])->whereNumber('schedule')->name('schedules.cancel');
});

Route::middleware(['auth', 'role:superadmin,admin,planner,operator'])->prefix('production')->name('production.')->group(function () {
    Route::get('/work-orders', [WorkOrderController::class, 'index'])->name('work_orders.index');
    Route::get('/work-orders/{workOrder}', [WorkOrderController::class, 'show'])->name('work_orders.show');
    Route::post('/work-orders/{workOrder}/claim', [WorkOrderController::class, 'claim'])->name('work_orders.claim');
    Route::post('/work-orders/{workOrder}/start', [WorkOrderController::class, 'start'])->name('work_orders.start');
    Route::post('/work-orders/{workOrder}/finish', [WorkOrderController::class, 'finish'])->name('work_orders.finish');
    Route::post('/work-orders/{workOrder}/materials', [WorkOrderController::class, 'storeMaterialUsage'])->name('work_orders.materials.store');
});

Route::middleware(['auth', 'role:superadmin,admin,planner'])->prefix('production')->name('production.')->group(function () {
    Route::post('/work-orders/{workOrder}/assign', [WorkOrderController::class, 'assign'])->name('work_orders.assign');
});

Route::middleware(['auth', 'role:superadmin,admin,inventory'])->prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/material-movements', [MaterialMovementController::class, 'index'])->name('material_movements.index');
    Route::post('/material-movements', [MaterialMovementController::class, 'store'])->name('material_movements.store');
});

Route::middleware(['auth', 'role:superadmin,admin,qc'])->group(function () {
    Route::get('/qc/inspections', [QualityCheckController::class, 'index'])->name('qc.inspections.index');
    Route::post('/qc/schedules/{schedule}/inspections', [QualityCheckController::class, 'store'])->whereNumber('schedule')->name('qc.inspections.store');
});

Route::middleware(['auth', 'role:superadmin,admin,planner'])->group(function () {
    Route::get('/costing', [ProductionCostController::class, 'index'])->name('costing.index');
    Route::post('/costing/schedules/{schedule}/compute', [ProductionCostController::class, 'compute'])->whereNumber('schedule')->name('costing.compute');
});

require __DIR__.'/auth.php';
