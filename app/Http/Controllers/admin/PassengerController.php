<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PassengerRequest;
use App\Models\Passenger;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PassengerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $passengers = Passenger::paginate(10);
        return response()->json([
            'status' => true,
            'data' => $passengers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PassengerRequest $request)
    {
        try {
            $validated = $request->validated();
            $passenger = Passenger::create($validated);
            return response()->json([
                'status' => true,
                'message' => 'added successfully'
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
            $passenger = Passenger::findOrFail($id);
            return response()->json([
                'status' => true,
                'data' => $passenger
            ]);
        } catch (ModelNotFoundException $ModelNotFoundEx) {
            return response()->json([
                'status' => false,
                'message' => 'passenger Not Found'
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
            $passenger = Passenger::findOrFail($id);
            $validated = $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:male,female',
                'nationality' => 'required'
            ]);
            $passenger->update($validated);
            return response()->json([
                'status' => true,
                'message' => 'updated Successfully'
            ]);
        } catch (ModelNotFoundException $ModelNotFoundEx) {
            return response()->json([
                'status' => false,
                'message' => 'passenger Not Found'
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
            $passenger = Passenger::findOrFail($id)->delete();
            return response()->json([
                'status' => true,
                'message' => 'deleted Successfully'
            ]);
        } catch (ModelNotFoundException $ModelNotFoundEx) {
            return response()->json([
                'status' => false,
                'message' => 'passenger Not Found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function searchPassengers($query)
    {
        $passenger = Passenger::where(function ($queryBuilder) use ($query) {
            $queryBuilder
                ->orWhere('first_name', 'LIKE', '%' . $query . '%')
                ->orWhere('last_name', 'LIKE', '%' . $query . '%')
                ->orWhere('date_of_birth', 'LIKE', '%' . $query . '%')
                ->orWhere('gender', 'LIKE', '%' . $query . '%')
                ->orWhere('nationality', 'LIKE', '%' . $query . '%');
        })->get();
        if (count($passenger)) {
            return Response()->json($passenger);
        } else {
            return response()->json(['message' => 'No Data not found'], 404);
        }
    }
}
