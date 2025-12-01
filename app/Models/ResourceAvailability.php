<?php

// File: app/Models/ResourceAvailability.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ResourceAvailability Model
 * 
 * Representerer åpningstider for en ressurs per ukedag.
 * Hver ressurs kan ha forskjellige åpningstider for hver dag i uken.
 */
class ResourceAvailability extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'resource_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'day_of_week' => 'integer',
    ];

    /**
     * Get the resource that owns the availability.
     *
     * @return BelongsTo
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}

// ResourceAvailability model representerer åpningstider for ressurser.
// Hver rad definerer start_time og end_time for en spesifikk ukedag (day_of_week).
// day_of_week: 0=Sunday, 1=Monday, ..., 6=Saturday

