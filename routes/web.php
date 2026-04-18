<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// new added imports
use App\Http\Controllers\AdminDashboardController;
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
    Route::get('/admin/dashboard', function () {
        return view('dashboard.admin');
    })->name('admin.dashboard');
});

Route::middleware(['auth', 'is_planner'])->group(function () {
    Route::get('/planner/dashboard', [PlannerDashboardController::class, 'index'])->name('planner.dashboard');
});

require __DIR__.'/auth.php';
