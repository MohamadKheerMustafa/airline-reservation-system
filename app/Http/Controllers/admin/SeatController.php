<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeatsRequest;
use App\Models\Seat;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Nette\Utils\Random;
use Illuminate\Support\Str;

class SeatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seats = Seat::with('flight')->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $seats
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SeatsRequest $request)
    {
        try {
            $validated = $request->validated();
            $seat = Seat::create([
                'flight_id' => $validated['flight_id'],
                'seat_number' => Str::random(3),
                'class' => $validated['class'],
                'availability' => $request->has('availability') ? $validated['availability'] : 0,
            ]);
            return response()->json([
                'status' => true,
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
            $seat = Seat::with('flight')->findOrFail($id);
            return response()->json([
                'status' => true,
                'data' => $seat
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
    public function update(SeatsRequest $request, $id)
    {
        try {
            $validated = $request->validated();
            $seats = Seat::findOrFail($id);
            $seats->update($validated);
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
            $seat = Seat::findOrFail($id)->delete();
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

    public function searchSeat($query)
    {
        $search = Seat::with('flight')
            ->orWhere(function ($queryBuilder) use ($query) {
                $queryBuilder
                    ->orWhereHas('flight', function ($search) use ($query) {
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
                    ->orWhere('seat_number', 'Like', '%' . $query . '%')
                    ->orWhere('class', 'Like', '%' . $query . '%');
            })
            ->paginate(10);

        return response()->json([
            'data' => $search
        ]);
    }


    public function sortBy(Request $request)
    {
        $seatNumber = 'seat_number';
        $class = 'class';
        $availability = 'availability';
        $sortBy = '';
        if ($request->SortBy == $seatNumber) {
            $sortBy = $seatNumber;
        } elseif ($request->SortBy == $class) {
            $sortBy = $class;
        } elseif ($request->SortBy == $availability) {
            $sortBy = $availability;
        } else {
            return 'unDefined';
        }
        $query = Seat::orderBy($sortBy, $request->query('orderBy'))->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $query
        ]);
    }
}
