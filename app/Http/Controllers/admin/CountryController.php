<?php

namespace App\Http\Controllers\admin;

use App\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CountryController extends Controller
{
    public function index()
    {
        $Countries = Country::latest()->paginate(20);
        return response()->json([
            'status' => true,
            'data' => $Countries
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:countries,name'
        ]);
        try {
            Country::create($validated);
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


    public function show($id)
    {
        try {
            $country = Country::findOrFail($id);
            return response()->json([
                'status' => true,
                'data' => $country
            ]);
        } catch (ModelNotFoundException $ModelNotFoundEx) {
            return response()->json([
                'status' => false,
                'message' => 'country Not Found',
                'content' => $ModelNotFoundEx->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|unique:countries,name,' . $id
        ]);
        try {
            $country = Country::findOrFail($id);
            $country->update($validated);

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
                'message' => 'country Not Found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $country = Country::findOrFail($id);
            $country->delete();
            return response()->json([
                'status' => true,
                'message' => 'Deleted Successfully'
            ]);
        } catch (ModelNotFoundException $ModelNotFoundEx) {
            return response()->json([
                'status' => false,
                'message' => 'country Not Found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getCountries()
    {
        $country = Country::get(['id', 'name']);
        return $country;
    }

    public function searchCountry($query)
    {
        $country = Country::where('name', 'LIKE', '%' . $query . '%')->get();
        if (count($country)) {
            return Response()->json($country);
        } else {
            return response()->json(['message' => 'No Data not found'], 404);
        }
    }
}
