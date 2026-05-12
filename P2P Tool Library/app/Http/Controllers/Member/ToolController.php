<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Tool;
<<<<<<< HEAD
use App\Models\User;
use App\Models\Category;
use App\Models\ToolDocument;
=======
>>>>>>> 8d0d19da599f4cc24cf668f06531e8ed97dc3973
use Illuminate\Http\Request;

class ToolController extends Controller
{
<<<<<<< HEAD
    public function index(Request $request)
    {
        $query = Tool::with('category', 'owner');

        // GEOSPATIAL DISCOVERY
        if ($request->filled('lat') && $request->filled('lng')) {
            $lat = $request->lat;
            $lng = $request->lng;
            
            // Haversine formula for distance in KM
            $query->select('*')
                ->selectRaw("(6371 * acos(cos(radians(?)) * cos(radians(location_lat)) * cos(radians(location_lng) - radians(?)) + sin(radians(?)) * sin(radians(location_lat)))) AS distance", [$lat, $lng, $lat])
                ->orderBy('distance', 'asc');
        }

        // BOOST LOGIC: Boosted tools always appear first
        $tools = $query->orderBy('is_boosted', 'desc')
                      ->orderBy('created_at', 'desc')
                      ->get();

        return view('member.tools.index', compact('tools'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('member.tools.create', compact('categories'));
=======
     public function index()
    {
        $tools = Tool::all();
        return response()->json($tools);
>>>>>>> 8d0d19da599f4cc24cf668f06531e8ed97dc3973
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'condition_status' => 'required|string',
            'category_id' => 'required|integer',
        ]);

        $tool = Tool::create([
            'title'            => $request->title,
            'price'            => $request->price,
            'description'      => $request->description,
            'condition_status' => $request->condition_status,
            'location_lat'     => $request->location_lat,
            'location_lng'     => $request->location_lng,
            'category_id'      => $request->category_id,
            'is_boosted'       => false,
            'location_lat'     => $request->lat ?? 30.0444,
            'location_lng'     => $request->lng ?? 31.2357,
            'compatibility_tags' => $request->compatibility_tags,
        ]);

<<<<<<< HEAD
        // Save Documentation if provided
        if ($request->manual_url) {
            ToolDocument::create([
                'tool_id' => $tool->id,
                'file_url' => $request->manual_url,
                'type' => 'manual'
            ]);
        }
        if ($request->video_url) {
            ToolDocument::create([
                'tool_id' => $tool->id,
                'file_url' => $request->video_url,
                'type' => 'video'
            ]);
        }

        return redirect()->route('member.dashboard')->with('success', 'Tool listed successfully with documentation!');
=======
        return response()->json($tool, 201);
>>>>>>> 8d0d19da599f4cc24cf668f06531e8ed97dc3973
    }

    public function show($id)
    {
<<<<<<< HEAD
        $tool = Tool::with(['category', 'owner', 'documents'])->findOrFail($id);
        return view('member.tools.show', compact('tool'));
    }
=======
        $tool = Tool::find($id);
>>>>>>> 8d0d19da599f4cc24cf668f06531e8ed97dc3973

        if (!$tool) {
            return response()->json(['message' => 'Tool not found'], 404);
        }

        return response()->json($tool);
    }

    public function update(Request $request, $id)
    {
        $tool = Tool::find($id);

        if (!$tool) {
            return response()->json(['message' => 'Tool not found'], 404);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'description' => 'sometimes|required|string',
            'condition_status' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|integer',
        ]);

        $tool->update($request->only([
<<<<<<< HEAD
            'title', 'price', 'description', 'condition_status', 'category_id', 'compatibility_tags'
=======
            'title', 'price', 'description', 'condition_status', 
            'location_lat', 'location_lng', 'category_id', 'is_boosted'
>>>>>>> 8d0d19da599f4cc24cf668f06531e8ed97dc3973
        ]));

        return response()->json($tool);
    }

    public function destroy($id)
    {
        $tool = Tool::find($id);

        if (!$tool) {
            return response()->json(['message' => 'Tool not found'], 404);
        }

        $tool->delete();

        return response()->json(['message' => 'Tool deleted successfully']);
    }
}

