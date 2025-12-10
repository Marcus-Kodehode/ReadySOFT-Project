<?php

// File: routes/web.php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\SlugController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
| Routes accessible to everyone without authentication
| Includes: Landing page, tenant booking pages, API endpoints
*/

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Public API Routes
Route::get('/api/check-slug', [SlugController::class, 'check'])->name('api.check-slug');
Route::get('/api/available-slots', [PublicBookingController::class, 'availableSlots'])
    ->middleware('throttle:60,1')
    ->name('api.available-slots');

// Demo route (development only)
Route::get('/components-demo', function () {
    return view('components-demo');
})->name('components.demo');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
| Routes for login, registration, password reset, etc.
| Provided by Laravel Breeze
*/

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| TENANT ROUTES (Subscription Middleware)
|--------------------------------------------------------------------------
| Routes for authenticated tenant admins with active subscriptions
| Middleware: auth, verified, subscription
*/

Route::middleware(['auth', 'verified', 'subscription'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Resource Management (Phase 6)
    Route::resource('resources', ResourceController::class);
    
    // Booking Management (Phase 9)
    Route::prefix('dashboard/bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/{id}', [BookingController::class, 'show'])->name('show');
        Route::patch('/{id}/status', [BookingController::class, 'updateStatus'])->name('updateStatus');
    });
    
    // SMS Settings (Phase 11)
    Route::prefix('dashboard/sms')->name('dashboard.sms')->group(function () {
        Route::get('/', [SmsController::class, 'index']);
        Route::post('/', [SmsController::class, 'update'])->name('.update');
        Route::post('/test', [SmsController::class, 'test'])->name('.test');
    });
    
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| SUBSCRIPTION MANAGEMENT ROUTES
|--------------------------------------------------------------------------
| Routes for handling inactive subscriptions
| Middleware: auth (no subscription check)
*/

Route::middleware('auth')->group(function () {
    Route::get('/subscription/inactive', [SubscriptionController::class, 'inactive'])
        ->name('subscription.inactive');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Admin Middleware)
|--------------------------------------------------------------------------
| Routes for system administrators only
| Middleware: auth, admin
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // Tenant Management
    Route::prefix('tenants')->name('tenants')->group(function () {
        Route::get('/', [AdminController::class, 'tenants']);
        Route::post('/{id}/toggle', [AdminController::class, 'toggleTenantStatus'])->name('.toggle');
    });
});

/*
|--------------------------------------------------------------------------
| PUBLIC BOOKING ROUTES
|--------------------------------------------------------------------------
| Routes for public booking pages (no authentication required)
| MUST BE LAST to avoid catching other routes with {slug} parameter
*/

Route::get('/booking/confirmation/{id}', [PublicBookingController::class, 'confirmation'])
    ->name('booking.confirmation');

Route::get('/{slug}', [PublicBookingController::class, 'show'])
    ->name('booking.show');

Route::post('/{slug}/bookings', [PublicBookingController::class, 'store'])
    ->middleware('throttle:10,60')
    ->name('booking.store');

// Routes are organized into clear groups:
// 1. PUBLIC ROUTES - No authentication required
// 2. AUTHENTICATION ROUTES - Laravel Breeze auth routes
// 3. TENANT ROUTES - Require auth + verified + subscription middleware
// 4. SUBSCRIPTION MANAGEMENT - Require auth only (for inactive subscription page)
// 5. ADMIN ROUTES - Require auth + admin middleware
// 6. PUBLIC BOOKING ROUTES - No auth, placed last to avoid slug conflicts
