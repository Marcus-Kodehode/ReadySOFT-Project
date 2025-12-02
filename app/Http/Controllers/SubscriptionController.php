<?php

// File: app/Http/Controllers/SubscriptionController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * SubscriptionController
 * 
 * Håndterer subscription-relaterte visninger og handlinger.
 */
class SubscriptionController extends Controller
{
    /**
     * Vis "Inactive Subscription" siden.
     * 
     * Denne siden vises når en bruker prøver å aksessere beskyttede ruter
     * uten en aktiv subscription.
     */
    public function inactive()
    {
        return view('subscription.inactive');
    }
}

// Controller for subscription-relaterte funksjoner.
// Viser informasjon og handlinger relatert til subscription-status.
