<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlaneRequest;
use App\Models\Flight;
use App\Models\Plane;
use App\Policies\PlanePolicy;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class planeController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Plane::class,'Plane');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plane = Plane::with('airline')->get();
        return response()->json([
            'status' => true,
            'data' => $plane
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlaneRequest $request)
    {
        try {
            $validated = $request->validated();
            Plane::create($validated);
            return response()->json([
                'status' => true,
                'message' => 'Added Successfully'
            ], 201);
        } catch (ValidationException $ValidationExc) {
            return response()->json([
                'status' => false,
                'errors' => $ValidationExc->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $plane = Plane::with('airline')->findOrFail($id);
            return response()->json([
                'status' => true,
                'data' => $plane
            ]);
        } catch (ModelNotFoundException $ModelNotFoundExc) {
            return response()->json([
                'status' => false,
                'message' => 'Plane id: ' . $id . ' Not Found.',
                'error' => $ModelNotFoundExc->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $plane = Plane::findOrFail($id);
            $request->validate([
                'name' => 'required|unique:planes,name,' . $id,
                'code' => 'required|unique:planes,code,' . $id,
                'capacity' => 'required',
                'airline_id' => 'required|exists:airlines,id',
                'manufacturer' => 'required',
                'year_manufactured' => 'required|integer',
            ]);
            $plane->update($request->only([
                'name',
                'code',
                'capacity',
                'airline_id',
                'manufacturer',
                'year_manufactured'
            ]));
            return response()->json([
                'status' => true,
                'message' => "Updated Successfully"
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Plane not found.',
                'errors' => $e->getMessage()
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => $e->validator->errors()->all()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something wrong',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            Plane::findOrFail($id)->delete();
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

    public function searchPlane($query)
    {
        $plane = Plane::whereHas('airline', function ($search) use ($query) {
            $search->where('name', 'LIKE', '%' . $query . '%');
        })
            ->orWhere(function ($queryBuilder) use ($query) {
                $queryBuilder
                    ->where('name', 'LIKE', '%' . $query . '%')
                    ->orWhere('code', 'LIKE', '%' . $query . '%')
                    ->orWhere('capacity', 'LIKE', '%' . $query . '%')
                    ->orWhere('manufacturer', 'LIKE', '%' . $query . '%');
            })
            ->get();
        if (count($plane)) {
            return Response()->json($plane);
        } else {
            return response()->json(['message' => 'No Data not found'], 404);
        }
    }
}
