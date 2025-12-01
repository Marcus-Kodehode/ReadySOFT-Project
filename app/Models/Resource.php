<?php

// File: app/Models/Resource.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Resource Model
 * 
 * Representerer en bookbar ressurs (hytte, stol, rom, behandlingsrom, etc.)
 * som tilhører en tenant. Hver ressurs har tilgjengelighet og kan motta bookinger.
 */
class Resource extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'type',
        'capacity',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'capacity' => 'integer',
    ];

    /**
     * Get the tenant that owns the resource.
     *
     * @return BelongsTo
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the availabilities for the resource.
     *
     * @return HasMany
     */
    public function availabilities(): HasMany
    {
        return $this->hasMany(ResourceAvailability::class);
    }

    /**
     * Get the bookings for the resource.
     *
     * @return HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}

// Resource model representerer bookbare ressurser i systemet.
// Hver ressurs tilhører en tenant og har tilgjengelighet (åpningstider) og bookinger.
// Tenant-isolasjon sikres gjennom tenant_id foreign key.
