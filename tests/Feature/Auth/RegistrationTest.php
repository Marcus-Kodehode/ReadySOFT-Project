<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    // Sørg for at vi har en plan i databasen
    \App\Models\Plan::factory()->create(['name' => 'Basic Plan']);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'business_name' => 'Test Business',
        'business_type' => 'Cabin Rental',
        'slug' => 'test-business',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('registration creates tenant, user and subscription in transaction', function () {
    // Sørg for at vi har en plan i databasen
    $plan = \App\Models\Plan::factory()->create(['name' => 'Basic Plan']);

    $response = $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'business_name' => 'Doe Salon',
        'business_type' => 'Hair Salon',
        'slug' => 'doe-salon',
    ]);

    // Verifiser at tenant ble opprettet
    $tenant = \App\Models\Tenant::where('slug', 'doe-salon')->first();
    expect($tenant)->not->toBeNull();
    expect($tenant->name)->toBe('Doe Salon');
    expect($tenant->business_type)->toBe('Hair Salon');
    expect($tenant->active)->toBeTrue();

    // Verifiser at user ble opprettet med tenant_id
    $user = \App\Models\User::where('email', 'john@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->name)->toBe('John Doe');
    expect($user->tenant_id)->toBe($tenant->id);
    expect($user->role)->toBe('tenant_admin');

    // Verifiser at subscription ble opprettet
    $subscription = \App\Models\Subscription::where('tenant_id', $tenant->id)->first();
    expect($subscription)->not->toBeNull();
    expect($subscription->plan_id)->toBe($plan->id);
    expect($subscription->active)->toBeTrue();
    expect($subscription->active_from)->not->toBeNull();

    // Verifiser at bruker er innlogget
    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('registration validation prevents duplicate slug', function () {
    // Opprett en eksisterende tenant med slug
    \App\Models\Tenant::factory()->create(['slug' => 'existing-salon']);
    \App\Models\Plan::factory()->create(['name' => 'Basic Plan']);

    $response = $this->post('/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'business_name' => 'Existing Salon',
        'business_type' => 'Hair Salon',
        'slug' => 'existing-salon', // Duplikat slug
    ]);

    // Verifiser at registrering feiler
    $response->assertSessionHasErrors('slug');
    
    // Verifiser at ingen ny user ble opprettet
    $user = \App\Models\User::where('email', 'jane@example.com')->first();
    expect($user)->toBeNull();
});

test('registration requires all tenant fields', function () {
    \App\Models\Plan::factory()->create(['name' => 'Basic Plan']);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        // Mangler business_name, business_type (slug er nå nullable og auto-genereres)
    ]);

    // Verifiser at registrering feiler (slug er ikke påkrevd lenger, genereres automatisk)
    $response->assertSessionHasErrors(['business_name', 'business_type']);
    
    // Verifiser at ingen data ble opprettet
    expect(\App\Models\User::where('email', 'test@example.com')->first())->toBeNull();
    expect(\App\Models\Tenant::count())->toBe(0);
});

test('registration auto-generates slug from business name when not provided', function () {
    \App\Models\Plan::factory()->create(['name' => 'Basic Plan']);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'business_name' => 'Salong Rosa',
        'business_type' => 'Hair Salon',
        // Slug ikke oppgitt - skal genereres automatisk
    ]);

    // Verifiser at tenant ble opprettet med auto-generert slug
    $tenant = \App\Models\Tenant::where('name', 'Salong Rosa')->first();
    expect($tenant)->not->toBeNull();
    expect($tenant->slug)->toBe('salong-rosa');
    
    // Verifiser at bruker er innlogget og redirected
    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('registration auto-generates unique slug when generated slug is taken', function () {
    // Opprett eksisterende tenant med slug "test-salon"
    \App\Models\Tenant::factory()->create(['slug' => 'test-salon']);
    \App\Models\Plan::factory()->create(['name' => 'Basic Plan']);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'business_name' => 'Test Salon', // Ville generere "test-salon" som er opptatt
        'business_type' => 'Hair Salon',
        // Slug ikke oppgitt - skal genereres automatisk med suffix
    ]);

    // Verifiser at tenant ble opprettet med alternativ slug
    $tenant = \App\Models\Tenant::where('name', 'Test Salon')->first();
    expect($tenant)->not->toBeNull();
    expect($tenant->slug)->not->toBe('test-salon'); // Skal ikke være den opptatte
    expect($tenant->slug)->toStartWith('test-salon-'); // Skal ha suffix
    
    // Verifiser at bruker er innlogget og redirected
    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('registration rolls back all data if transaction fails', function () {
    // Slett alle plans for å simulere en feil i transaksjonen
    // Når subscription prøver å opprette med Plan::first(), vil det feile
    \App\Models\Plan::query()->delete();
    
    // Tell antall records før registrering
    $tenantCountBefore = \App\Models\Tenant::count();
    $userCountBefore = \App\Models\User::count();
    $subscriptionCountBefore = \App\Models\Subscription::count();

    try {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'business_name' => 'Test Business',
            'business_type' => 'Cabin Rental',
            'slug' => 'test-business',
        ]);
    } catch (\Exception $e) {
        // Forventet feil - transaksjonen skal feile
    }

    // Verifiser at INGEN data ble opprettet (alt rullet tilbake)
    expect(\App\Models\Tenant::count())->toBe($tenantCountBefore);
    expect(\App\Models\User::count())->toBe($userCountBefore);
    expect(\App\Models\Subscription::count())->toBe($subscriptionCountBefore);
    
    // Verifiser spesifikt at ingen tenant med denne slugen eksisterer
    expect(\App\Models\Tenant::where('slug', 'test-business')->first())->toBeNull();
    
    // Verifiser spesifikt at ingen user med denne emailen eksisterer
    expect(\App\Models\User::where('email', 'test@example.com')->first())->toBeNull();
    
    // Verifiser at bruker IKKE er innlogget
    $this->assertGuest();
});
