<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\SlugController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\DashboardController;
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
