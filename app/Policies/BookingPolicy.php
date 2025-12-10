<?php

// File: app/Policies/BookingPolicy.php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

/**
 * BookingPolicy
 * 
 * Håndterer autorisasjon for Booking-modellen.
 * Sikrer at brukere kun kan aksessere bookinger for ressurser som tilhører deres tenant.
 */
class BookingPolicy
{
    /**
     * Determine if the user can view the booking.
     * 
     * Sjekker at bookingen tilhører en ressurs som tilhører samme tenant som brukeren.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return bool
     */
    public function view(User $user, Booking $booking): bool
    {
        return $booking->resource->tenant_id === $user->tenant_id;
    }

    /**
     * Determine if the user can update the booking.
     * 
     * Sjekker at bookingen tilhører en ressurs som tilhører samme tenant som brukeren.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return bool
     */
    public function update(User $user, Booking $booking): bool
    {
        return $booking->resource->tenant_id === $user->tenant_id;
    }

    /**
     * Determine if the user can delete the booking.
     * 
     * Sjekker at bookingen tilhører en ressurs som tilhører samme tenant som brukeren.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return bool
     */
    public function delete(User $user, Booking $booking): bool
    {
        return $booking->resource->tenant_id === $user->tenant_id;
    }
}

// BookingPolicy sikrer tenant-isolasjon ved å verifisere at brukere kun kan
// se, oppdatere og slette bookinger for ressurser som tilhører deres egen tenant.
