<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function index()
    {
        $tools = Tool::with(['category', 'owner'])->get();
        $categories = Category::all();
        return view('member.tools.index', compact('tools', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('member.tools.create', compact('categories'));
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
            'category_id'      => $request->category_id,
            'owner_id'         => auth()->id(),
            'is_boosted'       => false,
        ]);

        return redirect()->route('member.dashboard')->with('success', 'Tool listed successfully!');
    }

    public function show($id)
    {
        $tool = Tool::with(['category', 'owner'])->findOrFail($id);
        return view('member.tools.show', compact('tool'));
    }

    public function edit($id)
    {
        $tool = Tool::findOrFail($id);

        if ($tool->owner_id !== auth()->id()) {
            abort(403);
        }

        $categories = Category::all();
        return view('member.tools.edit', compact('tool', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $tool = Tool::find($id);

        if (!$tool) {
            return response()->json(['message' => 'Tool not found'], 404);
        }

        if ($tool->owner_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'description' => 'sometimes|required|string',
            'condition_status' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|integer',
        ]);

        $tool->update($request->only([
            'title', 'price', 'description', 'condition_status', 'category_id'
        ]));

        return redirect()->route('member.dashboard')->with('success', 'Tool updated successfully!');
    }

    public function destroy($id)
    {
        $tool = Tool::find($id);

        if (!$tool) {
            return response()->json(['message' => 'Tool not found'], 404);
        }

        if ($tool->owner_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $tool->delete();

        return redirect()->route('member.dashboard')->with('success', 'Tool deleted successfully!');
    }
}

