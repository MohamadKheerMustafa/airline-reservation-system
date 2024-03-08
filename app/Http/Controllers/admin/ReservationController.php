<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use App\Models\Flight;
use App\Models\Reservation;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservation = Reservation::with(['user', 'flights'])->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $reservation
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationRequest $request)
    {
        try {
            $validated = $request->validated();
            $flight = Flight::findOrFail($validated['flight_id']);
            $reservation = Reservation::create([
                'user_id' => $validated['user_id'],
                'flight_id' => $validated['flight_id'],
                'reservation_date' => $flight->departure,
                'seat_preference' => $validated['seat_preference'],
                'special_requests' => $validated['special_requests'],
                'addtionalNotes' => $validated['addtionalNotes']
            ]);
            return response()->json([
                'status' => true,
                'data' => $reservation,
                'message' => 'Added successfully',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $reservation = Reservation::with(['user', 'flights'])->findOrFail($id);
            return response()->json([
                'status' => true,
                'data' => $reservation
            ]);
        } catch (ModelNotFoundException $ModelNotFoundEx) {
            return response()->json([
                'status' => false,
                'message' => 'reservation Not Found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'flight_id' => 'required|exists:flights,id',
                'reservation_date' => 'required|date',
                'seat_preference' => 'nullable',
                'special_requests' => 'nullable',
            ]);
            $reservation = Reservation::findOrFail($id)->update($validated);
            return response()->json([
                'status' => true,
                'message' => 'updated Successfully'
            ]);
        } catch (ModelNotFoundException $ModelNotFoundEx) {
            return response()->json([
                'status' => false,
                'message' => 'reservation Not Found'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $reservation = Reservation::findOrFail($id)->delete();
            return response()->json([
                'status' => true,
                'message' => 'deleted Successfully'
            ]);
        } catch (ModelNotFoundException $ModelNotFoundEx) {
            return response()->json([
                'status' => false,
                'message' => 'reservation Not Found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getreservationForUser()
    {
        $reservation = Reservation::with(['user', 'flights'])->where('user_id', auth()->user()->id)->paginate(10);
        return $reservation;
    }

    public function searchReservation($query)
    {
        $reservations = Reservation::with(['user', 'flights'])->whereHas('user', function ($search) use ($query) {
            $search->orWhere('name', 'LIKE', '%' . $query . '%')
                ->orWhere('email', 'LIKE', '%' . $query . '%')
                ->orWhere('phone', 'LIKE', '%' . $query . '%')
                ->orWhere('address', 'LIKE', '%' . $query . '%');
        })
            ->orWhereHas('flights', function ($search) use ($query) {
                $search->orWhere(function ($queryBuilder) use ($query) {
                    $queryBuilder
                        ->where('flight_number', 'LIKE', '%' . $query . '%')
                        ->orWhere('gate_number', 'LIKE', '%' . $query . '%')
                        ->orWhere('departure', 'LIKE', '%' . $query . '%')
                        ->orWhere('arrival', 'LIKE', '%' . $query . '%')
                        ->orWhere('seats', 'LIKE', '%' . $query . '%')
                        ->orWhere('remain_seats', 'LIKE', '%' . $query . '%')
                        ->orWhere('status', 'LIKE', '%' . $query . '%')
                        ->orWhere('price', 'LIKE', '%' . $query . '%');
                });
            })
            ->orWhere(function ($queryBuilder) use ($query) {
                $queryBuilder
                    ->whereDate('reservation_date', 'LIKE', '%' . $query . '%')
                    ->orWhere('seat_preference', 'LIKE', '%' . $query . '%')
                    ->orWhere('special_requests', 'LIKE', '%' . $query . '%')
                    ->orWhere('addtionalNotes', 'LIKE', '%' . $query . '%');
            })
            ->get();

        if ($reservations->isEmpty()) {
            return response()->json(['message' => 'No Data not found'], 404);
        }

        return response()->json($reservations);
    }
}
