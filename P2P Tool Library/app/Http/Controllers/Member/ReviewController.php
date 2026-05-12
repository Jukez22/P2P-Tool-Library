<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reviewed_user_id' => 'required|exists:users,id',
            'rating'           => 'required|numeric|min:1|max:5',
            'comment'          => 'nullable|string|max:500',
        ]);

        Review::create([
            'reviewer_user_id' => auth()->id(),
            'reviewed_user_id' => $request->reviewed_user_id,
            'rating'           => $request->rating,
            'comment'          => $request->comment,
        ]);

        $reviewedUser = \App\Models\User::findOrFail($request->reviewed_user_id);
        $averageRating = $reviewedUser->reviewsReceived()->avg('rating');

        $reviewedUser->update([
            'trust_score' => round($averageRating, 1)
        ]);

        if ($reviewedUser->trust_score >= 4.5 && $reviewedUser->membership_tier_id == 1) {
            $reviewedUser->update(['membership_tier_id' => 2]); 
        }

        return back()->with('message', 'Review submitted and Trust Score updated!');
    }
}
