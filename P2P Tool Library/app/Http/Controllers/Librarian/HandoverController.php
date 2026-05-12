<?php

namespace App\Http\Controllers;

use App\Models\HandoverVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HandoverController extends Controller
{
    // Verify tool handover via QR
    public function verifyHandover(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $verification = HandoverVerification::where('qr_code', $request->qr_code)->first();

        if (!$verification) {
            return response()->json(['message' => 'Invalid QR code'], 404);
        }

        if ($verification->is_verified) {
            return response()->json(['message' => 'Already verified'], 422);
        }

        $verification->update([
            'is_verified' => true,
            'verified_at' => Carbon::now(),
        ]);

        return response()->json([
            'message' => 'Tool handover verified successfully',
            'data' => $verification
        ]);
    }
}
