<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\HandoverVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
            'borrower_id'    => Auth::id(), 
            'start_datetime' => $request->start_datetime,
            'end_datetime'   => $request->end_datetime,
            'total_price'    => $request->total_price,
            'status'         => 'Pending',
        ]);

        // Create Handover Verification record
        $reservation->handoverVerification()->create([
            'qr_code'     => Str::random(32),
            'is_verified' => false,
        ]);

        return response()->json($reservation->load('handoverVerification'), 201);
    }

    public function show(Request $request, $id)
    {
        $reservation = Reservation::with(['handoverVerification', 'tool'])->find($id);

        if (!$reservation) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Reservation not found'], 404);
            }
            abort(404);
        }

        if ($request->expectsJson()) {
            return response()->json($reservation);
        }

        return view('member.reservations.show', compact('reservation'));
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

    // Get the raw QR string for a reservation
    public function getReservationQR($id)
    {
        $reservation = Reservation::with('handoverVerification')->find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        if (!$reservation->handoverVerification) {
            return response()->json(['message' => 'Handover verification not found'], 404);
        }

        return response()->json([
            'qr_code' => $reservation->handoverVerification->qr_code
        ]);
    }
}

