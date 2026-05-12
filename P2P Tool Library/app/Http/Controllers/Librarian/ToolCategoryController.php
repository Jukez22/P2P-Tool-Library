<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;

use App\Models\ToolCategory;
use App\Models\Tool;
use App\Http\Requests\UpdateToolCategoryRequest;
use Illuminate\Http\Request;

class ToolCategoryController extends Controller
{

    public function createCategory(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|unique:tool_categories,name',
            'slug'        => 'nullable|string|unique:tool_categories,slug',
            'parent_id'   => 'nullable|exists:tool_categories,id',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string',
        ]);

        $slug = $request->slug ?: str($request->name)->slug()->toString();

        $category = ToolCategory::create([
            'name'        => $request->name,
            'slug'        => $slug,
            'parent_id'   => $request->parent_id,
            'description' => $request->description,
            'icon'        => $request->icon,
            'is_active'   => true,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Tool category created successfully',
                'data'    => $category
            ], 201);
        }

        return redirect()->back()->with('success', 'Category "' . $category->name . '" created successfully!');
    }

    public function updateCategory(UpdateToolCategoryRequest $request, $categoryId)
    {
        $category = ToolCategory::find($categoryId);

        if (!$category) {
            return response()->json(['message' => 'Tool category not found'], 404);
        }

        $category->update($request->validated());

        return response()->json([
            'message' => 'Tool category updated successfully',
            'data'    => $category
        ]);
    }

    public function assignToolToCategory(Request $request)
    {
        $request->validate([
            'tool_id'     => 'required|exists:tools,id',
            'category_id' => 'required|exists:tool_categories,id',
        ]);

        $category = ToolCategory::find($request->category_id);

        $category->tools()->syncWithoutDetaching([$request->tool_id]);

        return response()->json([
            'message' => 'Tool assigned to category successfully',
        ]);
    }

    public function getCategoryTree()
    {
        $categories = ToolCategory::whereNull('parent_id')
            ->with('children') 
            ->where('is_active', true)
            ->get();

        return response()->json([
            'data' => $categories
        ]);
    }

    public function getToolsByCategory($categoryId)
    {
        $category = ToolCategory::find($categoryId);

        if (!$category) {
            return response()->json(['message' => 'Tool category not found'], 404);
        }

        $tools = $category->tools()->with('categories')->get();

        return response()->json([
            'data' => $tools
        ]);
    }

    public function searchTools(Request $request)
    {
        $request->validate([
            'keyword'     => 'nullable|string',
            'category_id' => 'nullable|exists:tool_categories,id',
        ]);

        $query = \App\Models\Tool::query();

        if ($request->keyword) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->keyword}%")
                  ->orWhere('description', 'like', "%{$request->keyword}%");
            });
        }

        if ($request->category_id) {

            $categoryIds = $this->getDescendantIds($request->category_id);
            $categoryIds[] = (int) $request->category_id;

            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('tool_categories.id', $categoryIds);
            });
        }

        $tools = $query->with('categories')->get();

        return response()->json([
            'data' => $tools
        ]);
    }

    protected function getDescendantIds($parentId)
    {
        $childIds = ToolCategory::where('parent_id', $parentId)->pluck('id')->toArray();
        $allIds = $childIds;

        foreach ($childIds as $id) {
            $allIds = array_merge($allIds, $this->getDescendantIds($id));
        }

        return $allIds;
    }
}
