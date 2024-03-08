<?php

namespace App\Observers;

use App\Models\Luggage;
use App\Models\Payment;
use App\Models\Ticket;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        $reservation = $ticket->reservation_id;
        $tickets = Ticket::where('reservation_id', $reservation)->get();
        foreach ($tickets as $paymentForTicket) {
            Payment::create([
                'reservation_id' => $reservation,
                'payment_amount' => $paymentForTicket->price,
                'ticket_id' => $paymentForTicket->id,
            ]);
            
        }
    }

    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "deleted" event.
     */
    public function deleted(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "restored" event.
     */
    public function restored(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "force deleted" event.
     */
    public function forceDeleted(Ticket $ticket): void
    {
        //
    }
}
