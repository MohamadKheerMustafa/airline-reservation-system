<?php

use App\Models\Seat;
use App\Models\Flight;
use App\Models\Passenger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

function AddSeatsToFlight($counter, $flight, $class)
{
    if (!Flight::find($flight->id)) {
        throw new Exception("Flight Not Found", 1);
    }
    for ($i = 0; $i < $counter; $i++) {
        Seat::create([
            'flight_id' => $flight->id,
            'seat_number' => Str::random(3),
            'class' => $class,
            'availability' => 0,
        ]);
    }
    return 'done';
}

function CheckSeatExists($request, $seats, $flight)
{
    $availableSeats = [];

    foreach ($request as $seat) {
        $seatExists = $seats->where('flight_id', $flight->id)
            ->where('seat_number', $seat)
            ->exists();
        $seatTaken = $seats->where('flight_id', $flight->id)
            ->where('seat_number', $seat)->where('availability', 1)->first();

        if (!$seatExists) {
            // If any seat is unavailable, throw an exception
            throw new Exception("Seat number $seat is not available for this flight", 1);
        }
        if ($seatTaken) {
            throw new Exception("Seat number $seat is already has been taken", 1);
        }

        // If the seat is available, add it to the list of available seats
        $availableSeats[] = $seat;
    }

    // If all seats are available, return the array of seat numbers
    return $availableSeats;
}

function CheckPassengerAndCreated($passengerData)
{
    $check = Passenger::where('first_name', $passengerData['first_name'])
        ->where('last_name', $passengerData['last_name'])
        ->where('nationality', $passengerData['nationality'])
        ->where('date_of_birth', $passengerData['date_of_birth'])->first();
    if ($check) {
        // $passenger = $check;
        // throw new Exception('this User already exists', 1);
        $passenger = $check;
        // Log::alert();
        return $passenger;
    } else {
        $passenger = new Passenger($passengerData);
        $passenger->save();
        // Log::alert();
        return $passenger;
    }
}
