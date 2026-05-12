<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    // List all people the logged-in user has had conversations with
    public function index()
    {
        $userId = Auth::id();

        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->select(DB::raw('CASE WHEN sender_id = ' . $userId . ' THEN receiver_id ELSE sender_id END as contact_id'))
            ->distinct()
            ->get();

        $contacts = User::whereIn('id', $conversations->pluck('contact_id'))->get();
        
        return view('member.messages.index', compact('contacts'));
    }

    // View the chat history with a specific person
    public function show($contactId)
    {
        $userId = Auth::id();

        $messages = Message::where(function ($query) use ($userId, $contactId) {
                $query->where('sender_id', $userId)->where('receiver_id', $contactId);
            })
            ->orWhere(function ($query) use ($userId, $contactId) {
                $query->where('sender_id', $contactId)->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $contact = User::findOrFail($contactId);

        // Mark messages as read when viewing them
        Message::where('sender_id', $contactId)
            ->where('receiver_id', $userId)
            ->update(['is_read' => true]);

        return view('member.messages.show', compact('messages', 'contact'));
    }

    // Send a new message
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|integer|exists:users,id',
            'content'     => 'required|string',
        ]);

        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content'     => $request->content,
            'is_read'     => false,
        ]);

        return redirect()->route('member.dashboard', ['panel' => 'messages', 'contact_id' => $request->receiver_id])->with('success', 'Message sent!');
    }
}
