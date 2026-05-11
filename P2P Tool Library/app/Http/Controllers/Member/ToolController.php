<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Illuminate\Http\Request;

class ToolController extends Controller
{
     public function index()
    {
        $tools = Tool::all();
        return response()->json($tools);
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
        ]);

        return response()->json($tool, 201);
    }

    public function show($id)
    {
        $tool = Tool::find($id);

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
            'title', 'price', 'description', 'condition_status', 
            'location_lat', 'location_lng', 'category_id', 'is_boosted'
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

