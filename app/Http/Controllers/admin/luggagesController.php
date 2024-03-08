<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Luggage;
use App\Models\Reservation;
use Illuminate\Http\Request;

class luggagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $luggages = Luggage::with('ticket')->paginate(10);
        return $luggages;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'standard_quantity' => 'required',
            'additional_quantity' => 'nullable',
            'additional_price' => 'nulllable',
        ]);

        $luggage = Luggage::create([
            'ticket_id' => $request['ticket_id'],
            'standard_quantity' => 20,
            'additional_quantity' => $request['additional_quantity'],
            'additional_price' => $request['additional_price'],
        ]);
        return response()->json([
            'message' => 'added successfully',
            'data' => $luggage
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $luggage = Luggage::with('ticket')->findOrFail($id);

        return $luggage;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'ticket_id' => 'sometimes|required|exists:tickets,id',
            'standard_quantity' => 'sometimes|required',
            'additional_quantity' => 'sometimes|required',
            'additional_price' => 'sometimes|required',
        ]);

        $luggage = Luggage::findOrFail($id);
        $luggage->update($validated);
        return response()->json([
            'message' => 'updated successfully',
            'data' => $luggage,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Luggage::findOrFail($id)->delete();
        return response()->json([
            'message' => 'deleted succefully'
        ]);
    }
}
