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
        $user->load([
            'tools.category', 
            'tools.reservations',
            'reservations.tool', 
            'messagesReceived.sender', 
            'referrals.referredUser', 
            'trustScoreLogs', 
            'membershipTier'
        ]);

        // Calculate earnings this month (based on reservations for their tools)
        $earningsThisMonth = \App\Models\Reservation::whereHas('tool', function($q) use ($user) {
                $q->where('owner_id', $user->id);
            })
            ->whereIn('status', ['Active', 'Completed'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        $totalLent = \App\Models\Reservation::whereHas('tool', function($q) use ($user) {
                $q->where('owner_id', $user->id);
            })->count();
        $totalBorrowed = $user->reservations->count();
        $totalTransactions = $totalLent + $totalBorrowed;

        $categories = \App\Models\Category::all();

        // Fetch message contacts with their latest message
        $userId = Auth::id();
        $senderIds = \App\Models\Message::where('receiver_id', $userId)->pluck('sender_id');
        $receiverIds = \App\Models\Message::where('sender_id', $userId)->pluck('receiver_id');
        $contactIds = $senderIds->merge($receiverIds)->unique();
        
        $contacts = \App\Models\User::whereIn('id', $contactIds)
            ->get()
            ->map(function ($contact) use ($userId) {
                $lastMessage = \App\Models\Message::where(function ($query) use ($userId, $contact) {
                        $query->where('sender_id', $userId)->where('receiver_id', $contact->id);
                    })
                    ->orWhere(function ($query) use ($userId, $contact) {
                        $query->where('sender_id', $contact->id)->where('receiver_id', $userId);
                    })
                    ->latest()
                    ->first();
                $contact->last_message = $lastMessage;
                return $contact;
            })
            ->sortByDesc(function ($contact) {
                return $contact->last_message->created_at ?? 0;
            });

        // Fetch messages for selected contact
        $selectedContactId = request('contact_id', $contacts->first()->id ?? null);
        $activeMessages = collect();
        $selectedContact = null;

        if ($selectedContactId) {
            $selectedContact = \App\Models\User::find($selectedContactId);
            $activeMessages = \App\Models\Message::where(function ($query) use ($userId, $selectedContactId) {
                    $query->where('sender_id', $userId)->where('receiver_id', $selectedContactId);
                })
                ->orWhere(function ($query) use ($userId, $selectedContactId) {
                    $query->where('sender_id', $selectedContactId)->where('receiver_id', $userId);
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }

        // Fetch recent messages for the widget
        $recentMessages = \App\Models\Message::where('receiver_id', $userId)
            ->with('sender')
            ->latest()
            ->take(3)
            ->get();

        return view('member.dashboard', compact('user', 'categories', 'contacts', 'activeMessages', 'selectedContact', 'recentMessages', 'earningsThisMonth', 'totalTransactions'));
    }
}
