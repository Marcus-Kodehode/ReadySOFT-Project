<?php

// File: app/Models/Subscription.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Subscription Model
 * 
 * Representerer en subscription som kobler en tenant til en plan.
 * Håndterer aktiv status og subscription-perioder.
 */
class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'plan_id',
        'active',
        'active_from',
        'active_to',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'active_from' => 'datetime',
        'active_to' => 'datetime',
    ];

    /**
     * Get the tenant that owns the subscription.
     *
     * @return BelongsTo
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the plan that the subscription belongs to.
     *
     * @return BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}

// Subscription model kobler tenants til plans og håndterer aktiv status.
// Brukes av CheckActiveSubscription middleware for tilgangskontroll.
