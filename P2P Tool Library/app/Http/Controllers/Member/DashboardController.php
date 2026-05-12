<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Generate referral code if not exists
        if (!$user->referral_code) {
            $user->update(['referral_code' => 'REF-' . strtoupper(substr($user->name, 0, 3)) . '-' . $user->id]);
        }
        
        // Load relationships to avoid N+1 issues in the view
        $user->load(['tools', 'reservations.tool']);

        return view('member.dashboard', compact('user'));
    }
}
