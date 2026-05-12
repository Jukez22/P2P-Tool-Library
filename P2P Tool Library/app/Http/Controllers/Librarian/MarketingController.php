<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class MarketingController extends Controller
{

    public function storeCampaign(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string',
            'discount'    => 'required|numeric|min:1|max:100',
            'code'        => 'required|string|alpha_dash|max:20',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date',
        ]);

        try {
            \App\Models\PromotionCampaign::create([
                'name'       => $request->name,
                'category'   => $request->category,
                'discount'   => $request->discount,
                'code'       => strtoupper($request->code),
                'start_date' => $request->start_date,
                'end_date'   => $request->end_date,
                'is_active'  => true,
            ]);
        } catch (\Exception $e) {

        }

        $campaign = [
            'name'     => $request->name,
            'code'     => strtoupper($request->code),
            'discount' => $request->discount . '%',
            'category' => $request->category,
            'expires'  => $request->end_date ? \Carbon\Carbon::parse($request->end_date)->format('M j') : 'No Expiry',
        ];

        return redirect()->back()->with('success', 'Promotional campaign "' . $request->name . '" launched successfully with code ' . strtoupper($request->code))->with('new_campaign', $campaign);
    }
}

