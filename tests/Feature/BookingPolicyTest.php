<?php

// File: tests/Feature/BookingPolicyTest.php

use App\Models\Booking;
use App\Models\Plan;
use App\Models\Resource;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * BookingPolicy Test
 * 
 * Tester at BookingPolicy korrekt håndhever tenant-isolasjon.
 * Brukere skal kun kunne aksessere bookinger for ressurser som tilhører deres tenant.
 */

test('user can view booking for their own tenant resource', function () {
    // Arrange: Opprett tenant med aktiv subscription, bruker, ressurs og booking
    $tenant = Tenant::factory()->create();
    $plan = Plan::factory()->create();
    Subscription::factory()->create([
        'tenant_id' => $tenant->id,
        'plan_id' => $plan->id,
        'active' => true,
    ]);
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $resource = Resource::factory()->create(['tenant_id' => $tenant->id]);
    $booking = Booking::factory()->create(['resource_id' => $resource->id]);

    // Act & Assert: Sjekk at brukeren kan se bookingen
    expect($user->can('view', $booking))->toBeTrue();
});

test('user cannot view booking for another tenant resource', function () {
    // Arrange: Opprett to tenants med aktive subscriptions, hver sin bruker, ressurs og booking
    $plan = Plan::factory()->create();
    
    $tenant1 = Tenant::factory()->create();
    Subscription::factory()->create([
        'tenant_id' => $tenant1->id,
        'plan_id' => $plan->id,
        'active' => true,
    ]);
    
    $tenant2 = Tenant::factory()->create();
    Subscription::factory()->create([
        'tenant_id' => $tenant2->id,
        'plan_id' => $plan->id,
        'active' => true,
    ]);
    
    $user1 = User::factory()->create(['tenant_id' => $tenant1->id]);
    $resource2 = Resource::factory()->create(['tenant_id' => $tenant2->id]);
    $booking2 = Booking::factory()->create(['resource_id' => $resource2->id]);

    // Act & Assert: Sjekk at bruker fra tenant1 IKKE kan se booking fra tenant2
    expect($user1->can('view', $booking2))->toBeFalse();
});

test('user can update booking for their own tenant resource', function () {
    // Arrange: Opprett tenant med aktiv subscription, bruker, ressurs og booking
    $tenant = Tenant::factory()->create();
    $plan = Plan::factory()->create();
    Subscription::factory()->create([
        'tenant_id' => $tenant->id,
        'plan_id' => $plan->id,
        'active' => true,
    ]);
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $resource = Resource::factory()->create(['tenant_id' => $tenant->id]);
    $booking = Booking::factory()->create(['resource_id' => $resource->id]);

    // Act & Assert: Sjekk at brukeren kan oppdatere bookingen
    expect($user->can('update', $booking))->toBeTrue();
});

test('user cannot update booking for another tenant resource', function () {
    // Arrange: Opprett to tenants med aktive subscriptions, hver sin bruker, ressurs og booking
    $plan = Plan::factory()->create();
    
    $tenant1 = Tenant::factory()->create();
    Subscription::factory()->create([
        'tenant_id' => $tenant1->id,
        'plan_id' => $plan->id,
        'active' => true,
    ]);
    
    $tenant2 = Tenant::factory()->create();
    Subscription::factory()->create([
        'tenant_id' => $tenant2->id,
        'plan_id' => $plan->id,
        'active' => true,
    ]);
    
    $user1 = User::factory()->create(['tenant_id' => $tenant1->id]);
    $resource2 = Resource::factory()->create(['tenant_id' => $tenant2->id]);
    $booking2 = Booking::factory()->create(['resource_id' => $resource2->id]);

    // Act & Assert: Sjekk at bruker fra tenant1 IKKE kan oppdatere booking fra tenant2
    expect($user1->can('update', $booking2))->toBeFalse();
});

test('user can delete booking for their own tenant resource', function () {
    // Arrange: Opprett tenant med aktiv subscription, bruker, ressurs og booking
    $tenant = Tenant::factory()->create();
    $plan = Plan::factory()->create();
    Subscription::factory()->create([
        'tenant_id' => $tenant->id,
        'plan_id' => $plan->id,
        'active' => true,
    ]);
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $resource = Resource::factory()->create(['tenant_id' => $tenant->id]);
    $booking = Booking::factory()->create(['resource_id' => $resource->id]);

    // Act & Assert: Sjekk at brukeren kan slette bookingen
    expect($user->can('delete', $booking))->toBeTrue();
});

test('user cannot delete booking for another tenant resource', function () {
    // Arrange: Opprett to tenants med aktive subscriptions, hver sin bruker, ressurs og booking
    $plan = Plan::factory()->create();
    
    $tenant1 = Tenant::factory()->create();
    Subscription::factory()->create([
        'tenant_id' => $tenant1->id,
        'plan_id' => $plan->id,
        'active' => true,
    ]);
    
    $tenant2 = Tenant::factory()->create();
    Subscription::factory()->create([
        'tenant_id' => $tenant2->id,
        'plan_id' => $plan->id,
        'active' => true,
    ]);
    
    $user1 = User::factory()->create(['tenant_id' => $tenant1->id]);
    $resource2 = Resource::factory()->create(['tenant_id' => $tenant2->id]);
    $booking2 = Booking::factory()->create(['resource_id' => $resource2->id]);

    // Act & Assert: Sjekk at bruker fra tenant1 IKKE kan slette booking fra tenant2
    expect($user1->can('delete', $booking2))->toBeFalse();
});

// BookingPolicyTest verifiserer at tenant-isolasjon fungerer korrekt.
// Brukere kan kun aksessere bookinger for ressurser som tilhører deres egen tenant.
