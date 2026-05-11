<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Load relationships to avoid N+1 issues in the view
        $user->load(['tools', 'reservations.tool']);

        return view('member.dashboard', compact('user'));
    }
}
