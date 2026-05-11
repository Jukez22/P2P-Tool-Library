<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Show reservations where user is the borrower OR the tool owner
        $reservations = Reservation::where('borrower_id', $userId)
            ->orWhereHas('tool', function ($query) use ($userId) {
                $query->where('owner_id', $userId);
            })
            ->with(['tool', 'borrower']) // Optional: load relationships for better UI
            ->get();

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
            'borrower_id'    => auth()->id(), // Securely get the logged-in user's ID
            'start_datetime' => $request->start_datetime,
            'end_datetime'   => $request->end_datetime,
            'total_price'    => $request->total_price,
            'status'         => 'Pending', // Set default status to Pending
        ]);

        return response()->json($reservation, 201);
    }

    public function show($id)
    {
        $userId = auth()->id();
        $reservation = Reservation::with('tool')->find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        // Authorization: Must be borrower or tool owner
        if ($reservation->borrower_id !== $userId && $reservation->tool->owner_id !== $userId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($reservation);
    }

    public function update(Request $request, $id)
    {
        $userId = auth()->id();
        $reservation = Reservation::with('tool')->find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        // Authorization
        if ($reservation->borrower_id !== $userId && $reservation->tool->owner_id !== $userId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'sometimes|required|string',
            'start_datetime' => 'sometimes|required|date',
            'end_datetime' => 'sometimes|required|date|after:start_datetime',
            'total_price' => 'sometimes|required|numeric',
        ]);

        // Logic check: only owner can change status to "Completed" or "Cancelled" 
        // (Simplified for now, you can add more specific rules)
        
        $reservation->update($request->only([
            'start_datetime', 'end_datetime', 'status', 'total_price'
        ]));

        return response()->json($reservation);
    }

    public function destroy($id)
    {
        $userId = auth()->id();
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        // Only borrower can delete/cancel their own reservation request
        if ($reservation->borrower_id !== $userId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reservation->delete();

        return response()->json(['message' => 'Reservation deleted successfully']);
    }
}

