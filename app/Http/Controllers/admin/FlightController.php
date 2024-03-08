<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\FlightRequest;
use App\Http\Resources\FlightResource;
use App\Models\Flight;
use App\Models\Plane;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flight = Flight::with(['airline:id,name', "plane:id,name", 'origin:id,city_id,name', 'destination:id,city_id,name'])
            ->paginate(10);
        return FlightResource::collection($flight);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Creates a new flight record in the database based on the validated request data.
     *
     * @param FlightRequest $request An instance of the FlightRequest class containing the validated request data.
     * @return \Illuminate\Http\JsonResponse A JSON response with the status, message, and HTTP status code.
     */
    public function store(FlightRequest $request)
    {
        try {
            $AirlinePlane = Plane::where('airline_id', $request->airline_id)->pluck('id');
            $validated = $request->validated();
            $plane = Plane::findOrFail($validated['plane_id']);
            Flight::create([
                "flight_number" => rand(1000, 9999),
                "airline_id" => $validated['airline_id'],
                "plane_id" => in_array($validated['plane_id'], $AirlinePlane->toArray())
                    ? $validated['plane_id']
                    : abort(500, 'Plane id not for this airline'),
                "origin_id" => $validated['origin_id'],
                "destination_id" => $validated['destination_id'],
                "departure" => $validated['departure'],
                "arrival" => $validated['arrival'],
                "seats" => $plane->capacity,
                "remain_seats" => $plane->capacity,
                "status" => 0,
                "price" => $validated['price'],
                "gate_number" => $validated['gate_number'],
                "crew_id" => $validated['crew_id']
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Added Successfully'
            ], 201);
        } catch (ValidationException $ValidationExc) {
            return response()->json([
                'status' => false,
                'message' => $ValidationExc->getMessage(),
                'errors' => $ValidationExc->errors()
            ], 422);
        } catch (ModelNotFoundException $ModelNotFoundExc) {
            return response()->json([
                'status' => false,
                'message' => $ModelNotFoundExc->getMessage(),
                'error' => 'Plane ID Not Found'
            ], 404);
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
            $flight = Flight::findOrFail($id);
            return FlightResource::make($flight);
        } catch (ModelNotFoundException $ModelNotFoundExc) {
            return response()->json([
                'status' => false,
                'message' => 'Flight Not Found',
                'content' => $ModelNotFoundExc->getMessage()
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
            $AirlinePlane = Plane::where('airline_id', $request->airline_id)->pluck('id');
            $validated = $request->validate([
                'airline_id' => 'required|exists:airlines,id',
                'plane_id' => 'required|exists:planes,id',
                'origin_id' => 'required|exists:airports,id',
                'destination_id' => 'required|exists:airports,id',
                'departure' => 'required',
                'arrival' => 'required',
                'price' => 'required',
                'gate_number' => 'required|unique:flights,gate_number,' . $id,
                'crew_id' => 'required|exists:crews,id',
            ]);

            $plane = Plane::find($validated['plane_id']);
            $flight = Flight::findOrFail($id);
            $flight->update([
                "flight_number" => $flight->flight_number,
                "airline_id" => $validated['airline_id'],
                "plane_id" => in_array($validated['plane_id'], $AirlinePlane->toArray())
                    ? $validated['plane_id']
                    : abort(500, 'Plane id not for this airline'),
                "origin_id" => $validated['origin_id'],
                "destination_id" => $validated['destination_id'],
                "departure" => $validated['departure'],
                "arrival" => $validated['arrival'],
                "seats" => $plane->capacity,
                "remain_seats" => $flight->plane_id === $request->plane_id
                    ? $flight->remain_seats
                    : $plane->capacity,
                "status" => $flight->status,
                "price" => $validated['price'],
                "gate_number" => $validated['gate_number'],
                "crew_id" => $validated['crew_id']
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Updated Successfully'
            ]);
        } catch (ValidationException $ValidationExc) {
            return response()->json([
                'status' => false,
                'message' => $ValidationExc->getMessage(),
                'error' => $ValidationExc->errors()
            ], 422);
        } catch (ModelNotFoundException $ModelNotFoundExc) {
            return response()->json([
                'status' => false,
                'message' => $ModelNotFoundExc->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something Wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            Flight::findOrFail($id)->delete();
            return response()->json([
                'status' => true,
                'message' => 'Deleted Successfully'
            ]);
        } catch (ModelNotFoundException $ModelNotFoundExc) {
            return response()->json([
                'status' => false,
                'message' => 'Flight Not Found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something wrong',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function getPlanesByAirline(Request $request)
    {
        try {
            $planes = Plane::whereAirlineId($request->airline_id)->pluck('name', 'id');
            $planesArray = [];
            foreach ($planes as $key => $value) {
                $planesArray[] = [
                    "id" => $key,
                    "Plane" => $value,
                ];
            }
            return response()->json($planesArray);
        } catch (ModelNotFoundException $ModelNotFoundExc) {
            return response()->json([
                'status' => false,
                'message' => 'Model Not Found',
                'errors' => $ModelNotFoundExc->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function searchFlight($query)
    {
        $flights = Flight::where(function ($queryBuilder) use ($query) {
            $queryBuilder
                ->where('flight_number', 'LIKE', '%' . $query . '%')
                ->orWhere('gate_number', 'LIKE', '%' . $query . '%')
                ->orWhere('departure', 'LIKE', '%' . $query . '%')
                ->orWhere('arrival', 'LIKE', '%' . $query . '%')
                ->orWhere('seats', 'LIKE', '%' . $query . '%')
                ->orWhere('remain_seats', 'LIKE', '%' . $query . '%')
                ->orWhere('status', 'LIKE', '%' . $query . '%')
                ->orWhere('price', 'LIKE', '%' . $query . '%');
        })->orWhereHas('airline', function ($search) use ($query) {
            $search->where('name', 'LIKE', '%' . $query . '%');
        })->orWhereHas('plane', function ($search) use ($query) {
            $search->where('name', 'LIKE', '%' . $query . '%')
                ->orWhere('code', 'LIKE', '%' . $query . '%');
        })->orWhereHas('origin', function ($search) use ($query) {
            $search->where('name', 'LIKE', '%' . $query . '%');
        })->orWhereHas('crew', function ($search) use ($query) {
            $search->where('name', 'LIKE', '%' . $query . '%');
        })->orWhereHas('destination', function ($search) use ($query) {
            $search->where('name', 'LIKE', '%' . $query . '%');
        })->get();

        if ($flights->isEmpty()) {
            return response()->json(['message' => 'No Data not found'], 404);
        }

        return FlightResource::collection($flights);
    }
}
