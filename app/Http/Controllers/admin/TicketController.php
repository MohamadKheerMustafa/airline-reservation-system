<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\FlightResource;
use App\Http\Resources\TicketResource;
use App\Models\Flight;
use App\Models\Luggage;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use Illuminate\Validation\ValidationException;

class TicketController extends Controller
{
    public function index()
    {
        $Tickets = Ticket::with(['passenger', 'user', 'reservation', 'luggage'])->paginate(20);
        return TicketResource::collection($Tickets);
    }
    public function book(Request $request)
    {
        try {
            $reservationInfo = Reservation::findOrFail($request->reservation_id);
            $flight = $reservationInfo->flights;
            $seats = new Seat();
            if ($flight->remain_seats < count($request->passengers)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Not Enough Seats'
                ], 403);
            }
            $array = $request->seat_number;
            $helper = CheckSeatExists($array, $seats, $flight);
            // return $request->passengers[0]['first_name'];
            foreach ($request->passengers as $passengerData) {
                $passenger = CheckPassengerAndCreated($passengerData);
                $ticket = new Ticket([
                    'ticket_number' => Str::random(),
                    'reservation_id' => $request->reservation_id,
                    'user_id' => auth()->user()->id,
                    'seat_number' => $array[0],
                    'price' => $flight->price,
                    'passenger_id' => $passenger->id
                ]);
                $ticket->save();
                $seats->where('flight_id', $flight->id)
                    ->where('seat_number', $array[0])
                    ->update([
                        'availability' => 1
                    ]);
                array_shift($helper);
                array_shift($array);

                Payment::create([
                    'reservation_id' => $reservationInfo->id,
                    'payment_amount' => $flight->price,
                    'ticket_id' => $ticket->id,
                    'type' => 'flight'
                ]);
                Luggage::create([
                    'ticket_id' => $ticket->id,
                    'standard_quantity' => 20
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Added Successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function cancel($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);

            if ($ticket->user_id != auth()->user()->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Not Your Ticket'
                ], 403);
            }

            $ticket->reservation;
            $flight = Flight::findOrFail($ticket->reservation->flight_id);
            $flight->increment('remain_seats', 1);
            Payment::findOrFail($ticket->id)->delete();
            $ticket->delete();
            // $ticket->payment()->detach($id);

            return response()->json([
                'status' => true,
                'message' => 'Canceled Successfully'
            ]);;
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function userTickets() // ForUser
    {
        $data =  Ticket::where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')->get();
        return TicketResource::collection($data);
    }

    public function allFlights()
    {
        $flights = Flight::all();
        return $flights;
    }

    public function showFlights($user_id) // flights Not for User
    {
        $array = [];
        $arrayFlights = [];
        $Ticket = Ticket::where('user_id', $user_id)->get(['reservation_id']);
        foreach ($Ticket as $t) {
            array_push($array, $t->reservation_id);
        }
        $reservation = Reservation::whereIn('id', $array)->get();
        foreach ($reservation as $resID) {
            array_push($arrayFlights, $resID->flight_id);
        }
        $filghts = Flight::whereNotIn('id', $arrayFlights)->where('status', 0)->get();
        return FlightResource::collection($filghts);
    }

    public function showFlightsMe($user_id)
    {
        $array = [];
        $arrayFlights = [];
        $Ticket = Ticket::where('user_id', $user_id)->get(['reservation_id']);
        foreach ($Ticket as $t) {
            array_push($array, $t->reservation_id);
        }
        $reservation = Reservation::whereIn('id', $array)->get();
        foreach ($reservation as $resID) {
            array_push($arrayFlights, $resID->flight_id);
        }
        $filghts = Flight::whereIn('id', $arrayFlights)->get();
        return FlightResource::collection($filghts);
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            $payment = Payment::where('ticket_id', $id)->first();
            if ($payment->done == 0) {
                return ['message' => 'you have to pay your ticket First.'];
            } elseif ($payment->done == 1) {
                $ticket = Ticket::findOrFail($id);
                $status = 0;
                switch ($ticket->status) {
                    case 'approve':
                        return response()->json([
                            'status' => false,
                            'message' => 'You Cant update the status, already approved.'
                        ], 400);
                        break;
                    case 'cancele':
                        return response()->json([
                            'status' => false,
                            'message' => 'You Cant update the status, already canceled.'
                        ], 400);
                        break;
                }
                if ($request->status == 'approve') {
                    $status = 1;
                } else if ($request->status == 'cancele') {
                    $status = 2;
                }
                $ticket->update([
                    'status' => $status
                ]);
                $ticket->touch();
                $ticket->Employee = auth()->user()->name;
                $ticket->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Ticket status updated successfully.'
                ]);
            } else {
                return ['message' => 'your payment rejected please contact us for more informations'];
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function TicketsBooked()
    {
        $ticket = Ticket::where('status', 1)->get();
        return response()->json([
            'TicketsBookedCount' => count($ticket),
            'data' => TicketResource::collection($ticket)
        ]);
    }
    public function TicketsCancelled()
    {
        $ticket = Ticket::where('status', 2)->get();
        return response()->json([
            'TicketsPendingCount' => count($ticket),
            'data' => TicketResource::collection($ticket)
        ]);
    }
    public function TicketsPending()
    {
        $ticket = Ticket::where('status', 0)->get();
        return response()->json([
            'TicketsCancelledCount' => count($ticket),
            'data' => TicketResource::collection($ticket)
        ]);
    }

    public function seatsFlight($reservation_id)
    {
        $reservation = Reservation::findOrFail($reservation_id);
        $flight = Flight::with('seats')->findOrFail($reservation->flight_id);
        return $flight;
    }

    public function searchTicket($query)
    {
        $tickets = Ticket::with(['passenger', 'user', 'reservation'])
            ->orWhere(function ($queryBuilder) use ($query) {
                $queryBuilder
                    ->orWhereHas('user', function ($search) use ($query) {
                        $search->orWhere('name', 'LIKE', '%' . $query . '%')
                            ->orWhere('email', 'LIKE', '%' . $query . '%')
                            ->orWhere('phone', 'LIKE', '%' . $query . '%')
                            ->orWhere('address', 'LIKE', '%' . $query . '%');
                    })
                    ->orWhereHas('reservation', function ($search) use ($query) {
                        $search->orWhere(function ($queryBuilder) use ($query) {
                            $queryBuilder
                                ->whereDate('reservation_date', 'LIKE', '%' . $query . '%')
                                ->orWhere('seat_preference', 'LIKE', '%' . $query . '%')
                                ->orWhere('special_requests', 'LIKE', '%' . $query . '%')
                                ->orWhere('addtionalNotes', 'LIKE', '%' . $query . '%');
                        });
                    })
                    ->orWhereHas('passenger', function ($search) use ($query) {
                        $search->orWhere(function ($queryBuilder) use ($query) {
                            $queryBuilder->orWhere('first_name', 'LIKE', '%' . $query . '%')
                                ->orWhere('last_name', 'LIKE', '%' . $query . '%')
                                ->orWhere('date_of_birth', 'LIKE', '%' . $query . '%')
                                ->orWhere('gender', 'LIKE', '%' . $query . '%')
                                ->orWhere('nationality', 'LIKE', '%' . $query . '%');
                        });
                    });
            })
            ->orWhere(function ($search) use ($query) {
                $search->orWhere(function ($queryBuilder) use ($query) {
                    $queryBuilder->orWhere('ticket_number', 'LIKE', '%' . $query . '%')
                        ->orWhere('seat_number', 'LIKE', '%' . $query . '%')
                        ->orWhere('status', 'LIKE', '%' . $query . '%')
                        ->orWhere('price', 'LIKE', '%' . $query . '%')
                        ->orWhere('Employee', 'LIKE', '%' . $query . '%');
                });
            })
            ->get();

        if ($tickets->isEmpty()) {
            return response()->json(['message' => 'No Data not found'], 404);
        }

        return response()->json($tickets);
    }
}
