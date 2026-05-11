<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // View the logged-in user's profile edit page
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('membershipTier');
        return view('member.profile.edit', compact('user'));
    }

    public function show()
    {
        return redirect()->route('member.profile.edit');
    }

    // Update profile info (name, phone, address only)
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name'    => 'sometimes|required|string|max:50',
            'phone'   => 'sometimes|required|string|max:20',
            'address' => 'sometimes|nullable|string|max:255',
        ]);

        $user->update($request->only(['name', 'phone', 'address']));

        return back()->with('message', 'Profile updated successfully');
    }

    // Change the user's password securely
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required|string',
            'new_password'          => 'required|string|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Verify the current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 403);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('message', 'Password changed successfully');
    }
}
