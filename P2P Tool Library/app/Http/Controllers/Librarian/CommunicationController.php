<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CommunicationController extends Controller
{

    public function sendBroadcast(Request $request)
    {
        $request->validate([
            'zone'     => 'required|string',
            'audience' => 'required|string',
            'channel'  => 'required|string',
            'message'  => 'required|string|max:1000',
        ]);

        return redirect()->back()->with('success', 'Broadcast alert successfully transmitted via ' . $request->channel . ' to ' . $request->audience . ' within Zone: ' . $request->zone);
    }
}

