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
            'tool_id' => 'required|exists:tools,id',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
        ]);

        $tool = Tool::findOrFail($request->tool_id);
        
        // Calculate total price (simplified logic for demonstration)
        $start = new \DateTime($request->start_datetime);
        $end = new \DateTime($request->end_datetime);
        $days = max(1, $start->diff($end)->days + 1);
        $total_price = $tool->price * $days;

        $reservation = Reservation::create([
            'borrower_id'    => auth()->id(),
            'tool_id'        => $request->tool_id,
            'start_datetime' => $request->start_datetime,
            'end_datetime'   => $request->end_datetime,
            'total_price'    => $total_price,
            'status'         => 'Active',
        ]);

        return redirect()->route('member.dashboard')->with('success', 'Tool borrowed successfully!');
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
            'status' => 'required|string|in:Completed,Cancelled,Active',
        ]);

        $reservation->update(['status' => $request->status]);

        return redirect()->route('member.dashboard')->with('success', 'Reservation updated successfully!');
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

        return redirect()->route('member.dashboard')->with('success', 'Reservation cancelled successfully!');
    }
}

