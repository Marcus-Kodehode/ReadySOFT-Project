<?php

// File: tests/Feature/RouteGroupingTest.php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

/**
 * Test that routes are properly grouped with correct middleware
 */
class RouteGroupingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that public routes do not require authentication
     */
    public function test_public_routes_do_not_require_auth(): void
    {
        $publicRoutes = [
            'landing',
            'api.check-slug',
            'api.available-slots',
            'booking.show',
            'booking.store',
            'booking.confirmation',
        ];

        foreach ($publicRoutes as $routeName) {
            $route = Route::getRoutes()->getByName($routeName);
            $this->assertNotNull($route, "Route {$routeName} should exist");
            
            $middleware = $route->middleware();
            $this->assertNotContains('auth', $middleware, "Route {$routeName} should not require auth");
        }
    }

    /**
     * Test that tenant routes require subscription middleware
     */
    public function test_tenant_routes_require_subscription_middleware(): void
    {
        $tenantRoutes = [
            'dashboard',
            'resources.index',
            'bookings.index',
            'dashboard.sms',
        ];

        foreach ($tenantRoutes as $routeName) {
            $route = Route::getRoutes()->getByName($routeName);
            $this->assertNotNull($route, "Route {$routeName} should exist");
            
            $middleware = $route->middleware();
            $this->assertContains('auth', $middleware, "Route {$routeName} should require auth");
            $this->assertContains('subscription', $middleware, "Route {$routeName} should require subscription");
        }
    }

    /**
     * Test that admin routes require admin middleware
     */
    public function test_admin_routes_require_admin_middleware(): void
    {
        $adminRoutes = [
            'admin.dashboard',
            'admin.tenants',
            'admin.tenants.toggle',
        ];

        foreach ($adminRoutes as $routeName) {
            $route = Route::getRoutes()->getByName($routeName);
            $this->assertNotNull($route, "Route {$routeName} should exist");
            
            $middleware = $route->middleware();
            $this->assertContains('auth', $middleware, "Route {$routeName} should require auth");
            $this->assertContains('admin', $middleware, "Route {$routeName} should require admin");
        }
    }

    /**
     * Test that subscription inactive route requires auth but not subscription
     */
    public function test_subscription_inactive_route_requires_auth_only(): void
    {
        $route = Route::getRoutes()->getByName('subscription.inactive');
        $this->assertNotNull($route, "Route subscription.inactive should exist");
        
        $middleware = $route->middleware();
        $this->assertContains('auth', $middleware, "Route should require auth");
        $this->assertNotContains('subscription', $middleware, "Route should not require subscription");
    }

    /**
     * Test that all routes are properly named
     */
    public function test_all_routes_have_proper_names(): void
    {
        $expectedRoutes = [
            // Public
            'landing',
            'api.check-slug',
            'api.available-slots',
            'booking.show',
            'booking.store',
            'booking.confirmation',
            
            // Tenant
            'dashboard',
            'resources.index',
            'resources.create',
            'resources.store',
            'resources.show',
            'resources.edit',
            'resources.update',
            'resources.destroy',
            'bookings.index',
            'bookings.show',
            'bookings.updateStatus',
            'dashboard.sms',
            'dashboard.sms.update',
            'dashboard.sms.test',
            'profile.edit',
            'profile.update',
            'profile.destroy',
            
            // Subscription
            'subscription.inactive',
            
            // Admin
            'admin.dashboard',
            'admin.tenants',
            'admin.tenants.toggle',
        ];

        foreach ($expectedRoutes as $routeName) {
            $route = Route::getRoutes()->getByName($routeName);
            $this->assertNotNull($route, "Route {$routeName} should exist and be named");
        }
    }
}

// Test for route grouping - verifies middleware is correctly applied to route groups
