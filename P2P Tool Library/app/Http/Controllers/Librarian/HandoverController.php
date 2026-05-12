<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;

use App\Models\HandoverVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HandoverController extends Controller
{

    public function verifyHandover(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $verification = HandoverVerification::where('qr_code', $request->qr_code)->first();

        if (!$verification) {
            return redirect()->back()->with('error', 'Invalid QR code');
        }

        if ($verification->is_verified) {
            return redirect()->back()->with('error', 'Handover already verified');
        }

        $verification->update([
            'is_verified' => true,
            'verified_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Tool handover verified successfully!');
    }

    public function generateQR(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|string',
            'transfer_type'  => 'nullable|string',
        ]);

        $borrowId = preg_replace('/[^0-9]/', '', $request->reservation_id);

        $qrCode = 'QR-' . strtoupper(substr(md5(uniqid()), 0, 8));

        $verification = HandoverVerification::updateOrCreate(
            ['borrow_id' => $borrowId],
            [
                'qr_code'     => $qrCode,
                'is_verified' => false,
                'verified_at' => null,
            ]
        );

        return redirect()->back()->with('qr_generated', [
            'code'           => $qrCode,
            'reservation_id' => 'RES-' . $borrowId,
            'type'           => $request->transfer_type ?? 'Pickup'
        ])->with('success', 'QR Code generated successfully!');
    }
}
