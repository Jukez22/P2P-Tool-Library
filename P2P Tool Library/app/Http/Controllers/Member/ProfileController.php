<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // View the logged-in user's profile
    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('membershipTier');
        return response()->json($user);
    }

    // Update profile info (name, phone, address only)
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name'        => 'sometimes|required|string|max:50',
            'phone'       => 'sometimes|required|string|max:20',
            'address'     => 'sometimes|nullable|string|max:255',
            'national_id' => 'sometimes|nullable|string|max:20',
        ]);

        $user->update([
            'name'        => $request->name,
            'phone'       => $request->phone,
            'address'     => $request->address,
            'national_id' => $request->national_id,
            'is_verified' => $request->national_id ? 1 : 0,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Profile updated successfully',
                'user'    => $user,
            ]);
        }

        return back()->with('success', 'Profile and Verification updated successfully');
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

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }
}
