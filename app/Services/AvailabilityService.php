<?php

// File: app/Services/AvailabilityService.php

namespace App\Services;

use App\Models\Resource;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * AvailabilityService
 * 
 * Håndterer logikk for tilgjengelighet av ressurser.
 * Beregner ledige tidsluker basert på åpningstider og eksisterende bookinger.
 */
class AvailabilityService
{
    /**
     * Hent alle ledige tidsluker for en ressurs på en gitt dato.
     * 
     * Returnerer array av tilgjengelige tidspunkter basert på:
     * - Ressursens åpningstider for den aktuelle ukedagen
     * - Eksisterende bookinger som blokkerer tider
     * 
     * @param Resource $resource Ressursen det skal sjekkes tilgjengelighet for
     * @param string|Carbon $date Datoen det skal sjekkes (format: Y-m-d)
     * @return array Array av ledige tidspunkter (f.eks. ["09:00", "09:30", "10:00"])
     */
    public function getAvailableSlots(Resource $resource, $date): array
    {
        // Konverter til Carbon hvis string
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);
        
        // Hent ukedag (0=Sunday, 6=Saturday)
        $dayOfWeek = $date->dayOfWeek;
        
        // Hent åpningstider for denne ukedagen
        $availability = $resource->availabilities()
            ->where('day_of_week', $dayOfWeek)
            ->first();
        
        // Hvis ingen åpningstider definert for denne dagen, returner tom array
        if (!$availability) {
            return [];
        }
        
        // Hent eksisterende bookinger for denne datoen
        $existingBookings = $resource->bookings()
            ->where('booking_date', $date->format('Y-m-d'))
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();
        
        // Generer alle mulige tidsluker (30 minutters intervaller)
        $slots = $this->generateTimeSlots(
            $availability->start_time,
            $availability->end_time
        );
        
        // Filtrer bort opptatte tidsluker
        $availableSlots = $this->filterOccupiedSlots($slots, $existingBookings, $date);
        
        return $availableSlots;
    }
    
    /**
     * Generer tidsluker med 30 minutters intervaller.
     * 
     * @param string $startTime Start-tid (format: H:i:s eller H:i)
     * @param string $endTime Slutt-tid (format: H:i:s eller H:i)
     * @return array Array av tidspunkter
     */
    protected function generateTimeSlots(string $startTime, string $endTime): array
    {
        $slots = [];
        $current = Carbon::createFromFormat('H:i:s', $startTime);
        $end = Carbon::createFromFormat('H:i:s', $endTime);
        
        // Generer slots med 30 minutters intervaller
        while ($current->lt($end)) {
            $slots[] = $current->format('H:i');
            $current->addMinutes(30);
        }
        
        return $slots;
    }
    
    /**
     * Sjekk om et spesifikt tidsrom er ledig for booking basert på kapasitet.
     * 
     * Validerer at:
     * - Tidspunktet er innenfor åpningstider
     * - Antall overlappende bookinger < ressursens kapasitet
     * 
     * @param Resource $resource Ressursen det skal sjekkes for
     * @param string|Carbon $date Datoen (format: Y-m-d)
     * @param string $startTime Start-tid (format: H:i)
     * @param string $endTime Slutt-tid (format: H:i)
     * @return bool True hvis ledig, false hvis fullt booket
     */
    public function isTimeSlotAvailable(Resource $resource, $date, string $startTime, string $endTime): bool
    {
        // Konverter til Carbon hvis string
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);
        
        // Hent ukedag (0=Sunday, 6=Saturday)
        $dayOfWeek = $date->dayOfWeek;
        
        // Hent åpningstider for denne ukedagen
        $availability = $resource->availabilities()
            ->where('day_of_week', $dayOfWeek)
            ->first();
        
        // Hvis ingen åpningstider definert, er tiden ikke tilgjengelig
        if (!$availability) {
            return false;
        }
        
        // Konverter tider til Carbon for sammenligning
        // Bruk samme dato for alle tider for korrekt sammenligning
        $baseDate = $date->format('Y-m-d');
        $requestedStart = Carbon::createFromFormat('Y-m-d H:i', $baseDate . ' ' . $startTime);
        $requestedEnd = Carbon::createFromFormat('Y-m-d H:i', $baseDate . ' ' . $endTime);
        $openingStart = Carbon::createFromFormat('Y-m-d H:i:s', $baseDate . ' ' . $availability->start_time);
        $openingEnd = Carbon::createFromFormat('Y-m-d H:i:s', $baseDate . ' ' . $availability->end_time);
        
        // Sjekk om forespurt tid er innenfor åpningstider
        if ($requestedStart->lt($openingStart) || $requestedEnd->gt($openingEnd)) {
            return false;
        }
        
        // Tell antall overlappende bookinger
        $overlappingCount = $resource->bookings()
            ->where('booking_date', $date->format('Y-m-d'))
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($baseDate, $startTime, $endTime) {
                $query->whereRaw('TIME(end_time) > TIME(?)', [$startTime])
                      ->whereRaw('TIME(start_time) < TIME(?)', [$endTime]);
            })
            ->count();
        
        // Tilgjengelig hvis antall overlappende bookinger < capacity
        return $overlappingCount < $resource->capacity;
    }
    
    /**
     * Filtrer bort tidsluker som er fullt booket basert på ressursens kapasitet.
     * 
     * En slot er tilgjengelig hvis antall overlappende bookinger < capacity.
     * Dette tillater flere bookinger samtidig for ressurser med capacity > 1.
     * 
     * @param array $slots Alle mulige tidsluker
     * @param Collection $bookings Eksisterende bookinger
     * @param Carbon $date Datoen for sammenligning
     * @return array Ledige tidsluker
     */
    protected function filterOccupiedSlots(array $slots, Collection $bookings, Carbon $date): array
    {
        $baseDate = $date->format('Y-m-d');
        $resource = $bookings->first()?->resource ?? null;
        
        // Hvis ingen resource eller ingen bookinger, returner alle slots
        if (!$resource) {
            return $slots;
        }
        
        $capacity = $resource->capacity;
        
        return array_values(array_filter($slots, function ($slot) use ($bookings, $baseDate, $capacity) {
            $slotStart = Carbon::createFromFormat('Y-m-d H:i', $baseDate . ' ' . $slot);
            // En slot er 30 minutter lang
            $slotEnd = $slotStart->copy()->addMinutes(30);
            
            // Tell antall overlappende bookinger for denne sloten
            $overlappingCount = 0;
            foreach ($bookings as $booking) {
                $bookingStart = Carbon::createFromFormat('Y-m-d H:i:s', $baseDate . ' ' . $booking->start_time);
                $bookingEnd = Carbon::createFromFormat('Y-m-d H:i:s', $baseDate . ' ' . $booking->end_time);
                
                // Sjekk overlapp: to tidsperioder overlapper hvis:
                // slotStart < bookingEnd OG bookingStart < slotEnd
                if ($slotStart->lt($bookingEnd) && $bookingStart->lt($slotEnd)) {
                    $overlappingCount++;
                }
            }
            
            // Slot er tilgjengelig hvis antall overlappende bookinger < capacity
            return $overlappingCount < $capacity;
        }));
    }
}

// AvailabilityService håndterer beregning av ledige tidsluker for ressurser.
// Tar hensyn til åpningstider og eksisterende bookinger.
// Returnerer array av tilgjengelige tidspunkter med 30 minutters intervaller.
