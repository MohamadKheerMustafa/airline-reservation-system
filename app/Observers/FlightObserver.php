<?php

namespace App\Observers;

use App\Models\Flight;
use App\Models\Seat;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FlightObserver
{
    /**
     * Handle the Flight "created" event.
     */
    public function created(Flight $flight): void
    {
        //Add VIP Seats .
        $VIP = 20;
        $business = 50;
        $economy = $flight->seats - $business - $VIP;
        try {
            AddSeatsToFlight($VIP, $flight, 'VIP');
            AddSeatsToFlight($business, $flight, 'business');
            AddSeatsToFlight($economy, $flight, 'economy');
        } catch (Exception $e) {
            Log::alert($e->getMessage());
        }
    }

    /**
     * Handle the Flight "updated" event.
     */
    public function updated(Flight $flight): void
    {
        //
    }

    /**
     * Handle the Flight "deleted" event.
     */
    public function deleted(Flight $flight): void
    {
    }

    /**
     * Handle the Flight "restored" event.
     */
    public function restored(Flight $flight): void
    {
        //
    }

    /**
     * Handle the Flight "force deleted" event.
     */
    public function forceDeleted(Flight $flight): void
    {
        //
    }
}
