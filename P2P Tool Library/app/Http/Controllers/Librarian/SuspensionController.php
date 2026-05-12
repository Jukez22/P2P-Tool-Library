<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSuspension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class SuspensionController extends Controller
{

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

    public function applyRestriction(Request $request)
    {
        $request->validate([
            'member_id'   => 'required|string',
            'type'        => 'required|string',
            'reason'      => 'required|string|max:255',
        ]);

        $user = User::where('id', $request->member_id)
                    ->orWhere('email', $request->member_id)
                    ->orWhere('name', 'like', '%' . $request->member_id . '%')
                    ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Member not found matching given identifier.');
        }

        $days = null;
        $type = 'temporary';
        if (str_contains($request->type, '30')) {
            $days = 30;
        } elseif (str_contains($request->type, '60')) {
            $days = 60;
        } else {
            $type = 'permanent_ban';
        }

        UserSuspension::create([
            'user_id'         => $user->id,
            'type'            => $type,
            'reason'          => $request->reason,
            'suspended_until' => $days ? now()->addDays($days) : null,
            'is_active'       => true,
            'created_by'      => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Restriction successfully applied to ' . $user->name);
    }

    public function liftBan($id)
    {
        $suspension = UserSuspension::find($id);
        if ($suspension) {
            $suspension->delete();
        }
        return redirect()->back()->with('success', 'Restriction successfully lifted.');
    }
}
