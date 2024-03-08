<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Ticket;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::paginate(10);
        return PaymentResource::collection($payments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'payment_date' => 'required|data',
            'payment_method' => 'required',
            'ticket_id' => 'required|exists:tickets,id'
        ]);
        $ticketNumber = Ticket::findOrFail($request->ticket_id)->first();
        $payment = Payment::create([
            'reservation_id' => $request->reservation_id,
            'payment_amount' => $ticketNumber->price,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'ticket_id' => $request->ticket_id,
        ]);
        return response()->json([
            'message' => 'Added Successfully',
            'data' => $payment
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        return PaymentResource::make($payment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'payment_date' => 'sometimes|required|data',
            'payment_method' => 'sometimes|required',
            'done' => 'sometimes|required|in:0,1,2',
            'notes' => 'nullable',
        ]);
        $payment = Payment::findOrFail($id);
        if ($request->has('payment_date')) {
            if ($request->payment_date != null)
                $payment->payment_date = $request->payment_date;
        }
        if ($request->has('payment_method')) {
            if ($request->payment_method != null)
                $payment->payment_method = $request->payment_method;
        }
        if ($request->has('done')) {
            if ($request->done != null)
                $payment->done = $request->done;
        }
        if ($request->has('notes')) {
            if ($request->notes != null)
                $payment->notes = $request->notes;
        }
        $payment->Employee = auth()->user()->name;
        $payment->save();
        return response()->json([
            'message' => 'Updated Successfully',
            'data' => $payment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Payment::findOrFail($id)->delete();
        return response()->json([
            'message' => 'Deleted Successfully',
        ]);
    }

    public function paymentInfo($ticket_id)
    {
        $payment = Payment::where('ticket_id', $ticket_id)->get();
        return $payment;
    }

    public function PayTicket(Request $request)
    {
        // All I need in Request is reservation_id and Someinfo for Payment.
        $reservation = Reservation::findOrFail($request->reservation_id);
        $reservationTickets = $reservation->tickets;
        $paymentDate = $request->payment_date;
        $paymentMethod = $request->payment_method;
        $done = $request->done;
        $notes = $request->notes;
        foreach ($reservationTickets as $ticket) {
            $payment = Payment::where('ticket_id', $ticket->id)->first();
            $payment->update([
                'payment_date' => $paymentDate,
                'payment_method' => $paymentMethod,
                'done' => $request->has('done') ? $done : 0,
                'notes' => $request->notes,
                'Employee' => auth()->user()->name
            ]);
        }
        return response()->json([
            'message' => 'Payment done successfully'
        ]);
    }

    public function searchPayment($query)
    {
        $payment = Payment::with(['reservation', 'ticket'])->where(function ($queryBuilder) use ($query) {
            $queryBuilder
                ->orWhere('payment_amount', 'LIKE', '%' . $query . '%')
                ->orWhereDate('payment_date', 'LIKE', '%' . $query . '%')
                ->orWhere('payment_method', 'LIKE', '%' . $query . '%')
                ->orWhere('done', 'LIKE', '%' . $query . '%')
                ->orWhere('notes', 'LIKE', '%' . $query . '%')
                ->orWhere('Employee', 'LIKE', '%' . $query . '%');
        })
            ->orWhereHas('reservation', function ($search) use ($query) {
                $search->whereDate('reservation_date', 'LIKE', '%' . $query . '%');
            })
            ->orWhereHas('ticket', function ($search) use ($query) {
                $search->where('ticket_number', 'LIKE', '%' . $query . '%')
                    ->orWhere('seat_number', 'LIKE', '%' . $query . '%')
                    ->orWhere('status', 'LIKE', '%' . $query . '%')
                    ->orWhere('price', 'LIKE', '%' . $query . '%')
                    ->orWhere('Employee', 'LIKE', '%' . $query . '%');
            })
            ->get();
        if (count($payment)) {
            return Response()->json($payment);
        } else {
            return response()->json(['message' => 'No Data not found'], 404);
        }
    }

    public function userPayment()
    {
        $payment = Payment::whereHas('reservation', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->get();
        return $payment;
    }
}
