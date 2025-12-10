<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\Resource;
use App\Policies\BookingPolicy;
use App\Policies\ResourcePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrer policies for autorisasjon
        // ResourcePolicy sikrer at brukere kun kan aksessere ressurser i sin egen tenant
        // BookingPolicy sikrer at brukere kun kan aksessere bookinger for sine egne ressurser
        Gate::policy(Resource::class, ResourcePolicy::class);
        Gate::policy(Booking::class, BookingPolicy::class);
    }
}
