<?php

namespace App\Observers;

use App\Models\Luggage;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;

class LuggagesObserver
{
    /**
     * Handle the Luggage "created" event.
     */
    public function created(Luggage $luggage): void
    {
        //
    }

    /**
     * Handle the Luggage "updated" event.
     */
    public function updated(Luggage $luggage): void
    {
        if ($luggage->wasChanged('standard_quantity')) {
            $ticket = $luggage->ticket;
            Log::alert($ticket->reservation_id);
            $reservationInfo = Reservation::with('flights')->findOrFail($ticket->reservation_id);
            Payment::create([
                'reservation_id' => $reservationInfo->id,
                'payment_amount' => $luggage->additional_price,
                'ticket_id' => $ticket->id,
                'type' => 'luggages'
            ]);
        }
    }

    /**
     * Handle the Luggage "deleted" event.
     */
    public function deleted(Luggage $luggage): void
    {
        //
    }

    /**
     * Handle the Luggage "restored" event.
     */
    public function restored(Luggage $luggage): void
    {
        //
    }

    /**
     * Handle the Luggage "force deleted" event.
     */
    public function forceDeleted(Luggage $luggage): void
    {
        //
    }
}
