<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use Illuminate\Http\Request;
use App\Models\City;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CityController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(City::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $city = City::latest()->with('country')->get();
        return response()->json([
            'status' => true,
            'data' => $city
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CityRequest $request)
    {
        try {
            $validated = $request->validated();
            City::create($validated);
            return response()->json([
                'status' => true,
                'message' => 'Added Successfully'
            ]);
        } catch (ValidationException $ValidationEx) {
            return response()->json([
                'status' => false,
                'message' => $ValidationEx->getMessage(),
                'errors' => $ValidationEx->errors()
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
            $city = City::with('country')->findOrFail($id);
            return response()->json([
                'status' => true,
                'data' => $city
            ]);
        } catch (ModelNotFoundException $ModelNotFoundEx) {
            return response()->json([
                'status' => false,
                'message' => 'City Not Found'
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
            $city = City::findOrFail($id);
            $request->validate([
                'country_id' => 'required|exists:countries,id',
                'name' => 'required|string|unique:cities,name,' . $id
            ]);
            $city->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Updated Successfully'
            ]);
        } catch (ValidationException $ValidationEx) {
            return response()->json([
                'status' => false,
                'message' => $ValidationEx->getMessage(),
                'errors' => $ValidationEx->errors()
            ], 422);
        } catch (ModelNotFoundException $ModelNotFoundEx) {
            return response()->json([
                'status' => false,
                'message' => 'City Not Found'
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
            City::findOrFail($id)->delete();
            return response()->json([
                'status' => true,
                'message' => 'Deleted Successfully'
            ]);
        } catch (ModelNotFoundException $ModelNotFoundEx) {
            return response()->json([
                'status' => false,
                'message' => 'City Not Found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function searchCity($query)
    {
        $city = City::with('country')->whereHas('country', function ($search) use ($query) {
            $search->where('name', 'LIKE', '%' . $query . '%');
        })->orwhere('name', 'LIKE', '%' . $query . '%')->get();
        if (count($city)) {
            return Response()->json($city);
        } else {
            return response()->json(['message' => 'No Data not found'], 404);
        }
    }
}
