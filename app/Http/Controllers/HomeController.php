<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Plane;
use App\Models\Reservation;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function statistics()
    {
        $totalAirline = Airline::count();
        $totalCustomer = User::whereIsAdmin(0)->count();
        $totalPlane = Plane::count();
        $totalAirport = Airport::count();
        $totalFlight = Flight::count();
        $totalTicket = Ticket::count();
        $totalReservations = Reservation::count();
        $totalPassenger = Passenger::count();
        $totalPaymentCount = Payment::count();
        $totalPaymentPrice = Payment::all()->sum('payment_amount');
        // get last 10 flights
        $lastFlights = Flight::orderBy('id', 'desc')->take(10)->get();

        // get active ariline by number of flights
        $activeAirlines = Airline::query()
            ->withCount('flights')
            ->withCount('planes')
            ->orderBy('flights_count', 'desc')
            ->take(6)
            ->get();

        // CHARTS DATA CONFIG
        // get status of flights
        $flightStatusChart = DB::table('flights')
            ->orderBy('status', 'desc')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->map(function ($item) {
                switch (trim($item->status)) {
                    case 0:
                        $item->label = "Land";
                        $item->color = "#ea868f";
                        break;
                    case 1:
                        $item->label = "Take Off";
                        $item->color = "#20c997";
                        break;
                }
                return (array) $item;
            })->toArray();

        $data = [
            'totalAirline'      => $totalAirline,
            'totalPlane'        => $totalPlane,
            'totalAirport'      => $totalAirport,
            'totalFlight'       => $totalFlight,
            'totalTicket'       => $totalTicket,
            'totalCustomer'     => $totalCustomer,
            "totalReservations" => $totalReservations,
            "totalPassenger" => $totalPassenger,
            "totalPaymentCount" => $totalPaymentCount,
            "totalPaymentPrice" => $totalPaymentPrice,
            'lastFlights'       => $lastFlights,
            "activeAirlines"    => $activeAirlines,
            "flightStatusChart" => $flightStatusChart,
        ];
        return $data;
    }
}
