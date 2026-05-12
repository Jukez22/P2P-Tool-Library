<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;

use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    // Create new Zone from dashboard
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'city' => 'nullable|string|max:100',
        ]);

        Zone::create([
            'name'    => $request->name,
            'city'    => $request->city ?? 'Cairo',
            'country' => 'Egypt',
        ]);

        return redirect()->back()->with('success', 'Zone "' . $request->name . '" created successfully!');
    }
}

