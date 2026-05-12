<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSuspension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class SuspensionController extends Controller
{
    // Permanently ban user
    public function banUser(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $suspension = UserSuspension::create([
            'user_id'         => $id,
            'type'            => 'permanent',
            'reason'          => $request->reason,
            'suspended_until' => null,
            'is_active'       => true,
            'created_by'      => auth()->id(),
        ]);

        return response()->json([
            'message' => 'User permanently banned',
            'data'    => $suspension,
        ], 201);
    }

    // Temporary suspension (X days)
    public function suspendUser(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'days'   => 'required|integer|min:1|max:365',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $suspension = UserSuspension::create([
            'user_id'         => $id,
            'type'            => 'temporary',
            'reason'          => $request->reason,
            'suspended_until' => Date::now()->addDays($request->days),
            'is_active'       => true,
            'created_by'      => auth()->id(),
        ]);

        return response()->json([
            'message' => 'User suspended successfully',
            'data'    => $suspension,
        ], 201);
    }
}
