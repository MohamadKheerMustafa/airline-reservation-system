<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AirlineRequest;
use App\Models\Airline;
use App\Models\Plane;
use App\Models\Airport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class AirlineController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Airline::class,'Airline');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $air = Airline::withCount('planes')->get();
            return response()->json([
                'status' => true,
                'data' => $air
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AirlineRequest $request)
    {
        try {
            $validated = $request->validated();
            Airline::create($validated);
            return response()->json([
                'status' => true,
                'message' => "Added Succussfully"
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
                'errors' => $exception->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // $this->authorize('view',$id);
            $airline = Airline::withCount('planes')->findOrFail($id);
            return response()->json([
                'status' => true,
                'data' => $airline->load('planes')
            ]);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Airline not found.',
                'content' => $exception->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $airline = Airline::findOrFail($id);
            $request->validate([
                'name' => 'required|unique:airlines,name,' . $id,
                'code' => 'required|unique:airlines,code,' . $id,
            ]);
            $airline->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Updated Successfully'
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
                'errors' => $exception->errors()
            ], 422);
        } catch (ModelNotFoundException $modelException) {
            return response()->json([
                'status' => false,
                'message' => 'Airline not found.',
                'content' => $modelException->getMessage()
            ], 404);
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
            Airline::findOrFail($id)->delete();
            return response()->json([
                'status' => true,
                'message' => 'Deleted Succussfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Airline Not Found',
                'content' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAirline()
    {
        $airline = Airline::get(['id', 'name']);
        return response()->json([
            'data' => $airline
        ]);
    }

    public function getPlane()
    {
        $plane = Plane::get(['id', 'name']);
        return response()->json([
            'data' => $plane
        ]);
    }

    public function getAirport()
    {
        $airport = Airport::get(['id', 'name']);
        return response()->json([
            'data' => $airport
        ]);
    }

    public function searchAirline($query)
    {
        $airline = Airline::where('name', 'LIKE', '%' . $query . '%')
            ->orwhere('code', 'LIKE', '%' . $query . '%')->get();
        if (count($airline)) {
            return Response()->json($airline);
        } else {
            return response()->json(['message' => 'No Data not found'], 404);
        }
    }
}
