<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AirportRequest;
use App\Models\Airport;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AirportController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Airport::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $airport = Airport::latest()->withCount('city')->get();
        return response()->json([
            'status' => true,
            'data' => $airport
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AirportRequest $request)
    {
        try {
            Airport::create($request->validated());
            return response()->json([
                'status' => true,
                'message' => 'Added Successfully',
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
            $airport = Airport::with('city')->findOrFail($id);
            return response()->json([
                'status' => true,
                'data' => $airport
            ]);
        } catch (ModelNotFoundException $ModelNotFoundEx) {
            return response()->json([
                'status' => false,
                'message' => 'Airport Not Found',
                'content' => $ModelNotFoundEx->getMessage()
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
            $airport = Airport::findOrFail($id);
            $request->validate([
                'name' => 'required|string|unique:airports,name,' . $id,
                'city_id' => 'required|exists:cities,id'
            ]);
            $airport->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Updated Successfully'
            ]);
        } catch (ValidationException $validationEx) {
            return response()->json([
                'status' => false,
                'message' => $validationEx->getMessage(),
                'errors' => $validationEx->errors()
            ], 422);
        } catch (ModelNotFoundException $ModelNotFoundEx) {
            return response()->json([
                'status' => false,
                'message' => 'Airport Not Found'
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
            Airport::findOrFail($id)->delete();
            return response()->json([
                'status' => true,
                'message' => 'Deleted Successfully'
            ]);
        } catch (ModelNotFoundException $ModelNotFoundEx) {
            return response()->json([
                'status' => false,
                'message' => 'Airport Not Found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function searchAirport($query)
    {
        $airport = Airport::with('city')->whereHas('city', function ($search) use ($query) {
            $search->where('name', 'LIKE', '%' . $query . '%');
        })->orwhere('name', 'LIKE', '%' . $query . '%')->get();
        if (count($airport)) {
            return Response()->json($airport);
        } else {
            return response()->json(['message' => 'No Data not found'], 404);
        }
    }
}
