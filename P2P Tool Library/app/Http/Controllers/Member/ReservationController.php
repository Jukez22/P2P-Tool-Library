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
            ->with(['tool.owner', 'borrower'])
            ->get();

        return view('member.dashboard', compact('reservations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $tool = Tool::findOrFail($request->tool_id);
        
        // BUFFER PERIOD LOGIC (1 Day)
        $requestedStart = new \DateTime($request->start_date);
        $requestedEnd = new \DateTime($request->end_date);

        // Check for overlaps with existing reservations + 1 day buffer
        $existingReservations = Reservation::where('tool_id', $request->tool_id)
            ->whereIn('status', ['Pending', 'Active', 'Confirmed'])
            ->get();

        foreach ($existingReservations as $res) {
            $resStart = (new \DateTime($res->start_datetime))->modify('-1 day'); // Buffer before
            $resEnd = (new \DateTime($res->end_datetime))->modify('+1 day');   // Buffer after

            // Overlap check: (StartA <= EndB) and (EndA >= StartB)
            if ($requestedStart <= $resEnd && $requestedEnd >= $resStart) {
                return back()->withErrors(['dates' => 'This tool is unavailable during these dates due to other bookings or cleaning buffer periods (1 day).']);
            }
        }

        // Calculate days for pricing
        $days = $requestedStart->diff($requestedEnd)->days;
        if($days == 0) $days = 1;

        $total_price = $days * $tool->price;
        $deposit_amount = $tool->deposit_price;

        Reservation::create([
            'tool_id'        => $request->tool_id,
            'borrower_id'    => auth()->id(),
            'start_datetime' => $request->start_date,
            'end_datetime'   => $request->end_date,
            'total_price'    => $total_price,
            'deposit_amount' => $deposit_amount,
            'status'         => 'Pending',
        ]);

        // Update user escrow
        $user = auth()->user();
        $user->increment('escrow_balance', $deposit_amount);

        return redirect()->route('member.dashboard')->with('success', 'Reservation request sent! ' . $deposit_amount . ' EGP held in escrow.');
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

        return back()->with('success', 'Reservation cancelled successfully.');
    }
}

