<?php

// File: app/Models/Booking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Booking Model
 * 
 * Representerer en booking av en ressurs. Inneholder kunde-informasjon,
 * dato/tid for booking, og status. Hver booking tilhører en ressurs.
 */
class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'resource_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'booking_date',
        'start_time',
        'end_time',
        'notes',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'booking_date' => 'date',
    ];

    /**
     * Get the resource that owns the booking.
     *
     * @return BelongsTo
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}

// Booking model representerer bookinger i systemet.
// Hver booking tilhører en ressurs og inneholder kunde-informasjon og tidspunkt.
// Status kan være: pending, confirmed, eller cancelled.
