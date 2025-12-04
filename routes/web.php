<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\SlugController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResourceController;
use Illuminate\Support\Facades\Route;

// API Routes
Route::get('/api/check-slug', [SlugController::class, 'check'])->name('api.check-slug');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Subscription Routes
Route::get('/subscription/inactive', [SubscriptionController::class, 'inactive'])
    ->middleware('auth')
    ->name('subscription.inactive');

// Resource Management Routes (Phase 6)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('resources', ResourceController::class);
});

// Placeholder routes for Quick Actions (to be implemented in later phases)
Route::middleware(['auth', 'verified'])->group(function () {
    // SMS Settings route (Phase 8)
    Route::get('/dashboard/sms', function () {
        return redirect()->route('dashboard')->with('info', 'SMS settings coming soon!');
    })->name('dashboard.sms');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
