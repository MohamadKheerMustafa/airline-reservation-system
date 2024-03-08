<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CrewMemberRequest;
use App\Models\Airline;
use App\Models\Crew;
use App\Models\CrewMember;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CrewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $crews = Crew::with('airlines')->withCount('crewMembers')->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $crews
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //First Crew create.
            $validated = $request->validate([
                'airline_id' => 'required|exists:airlines,id', // For Crew
                'name' => 'required' //For Crew
            ]);
            $crews = Crew::create($validated);
            return response()->json([
                'status' => true,
                'message' => 'added Successfully'
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

    public function storeCrewsMember(CrewMemberRequest $request)
    {
        try {
            $validated = $request->validated();
            $crews = CrewMember::create($validated);
            return response()->json([
                'status' => true,
                'message' => 'added Successfully'
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
            $crew = Crew::with(['crewMembers', 'airlines'])->findOrFail($id);
            return response()->json([
                'status' => true,
                'data' => $crew
            ]);
        } catch (ModelNotFoundException $ModelNotFoundEx) {
            return response()->json([
                'status' => false,
                'message' => 'crew Not Found'
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
            $crews = Crew::findOrFail($id);
            $crews->delete();
            return response()->json([
                'status' => true,
                'message' => 'Deleted Successfully'
            ]);
        } catch (ModelNotFoundException $ModelNotFoundEx) {
            return response()->json([
                'status' => false,
                'message' => 'crew Not Found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function searchCrew($query)
    {
        $crew = Crew::with('airlines')->whereHas('airlines', function ($search) use ($query) {
            $search->where('name', 'LIKE', '%' . $query . '%');
        })->orWhere('name', 'LIKE', '%' . $query . '%')->get();
        if (count($crew)) {
            return Response()->json($crew);
        } else {
            return response()->json(['message' => 'No Data not found'], 404);
        }
    }

    public function getCrewsByAirline($airline_id)
    {
        $airline = Airline::with('crews')->findOrFail($airline_id);
        return $airline;
    }
}
