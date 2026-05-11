<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::all();
        return response()->json($reservations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|integer',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'total_price' => 'required|numeric',
        ]);

        $reservation = Reservation::create([
            'tool_id'        => $request->tool_id,
            'borrower_id'    => Auth::id(), // Securely get the logged-in user's ID
            'start_datetime' => $request->start_datetime,
            'end_datetime'   => $request->end_datetime,
            'total_price'    => $request->total_price,
            'status'         => 'Pending', // Set default status to Pending
        ]);

        return response()->json($reservation, 201);
    }

    public function show($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        return response()->json($reservation);
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        $request->validate([
            'status' => 'sometimes|required|string',
            'start_datetime' => 'sometimes|required|date',
            'end_datetime' => 'sometimes|required|date|after:start_datetime',
            'total_price' => 'sometimes|required|numeric',
        ]);

        $reservation->update($request->only([
            'start_datetime', 'end_datetime', 'status', 'total_price'
        ]));

        return response()->json($reservation);
    }

    public function destroy($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        $reservation->delete();

        return response()->json(['message' => 'Reservation deleted successfully']);
    }
}

