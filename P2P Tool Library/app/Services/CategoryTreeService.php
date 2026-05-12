<?php

namespace App\Services;

use App\Models\ToolCategory;

// Manage hierarchical stuff for categories
class CategoryTreeService
{
    // Build the full tree
    public function buildTree()
    {
        $allCategories = ToolCategory::where('is_active', true)->get();

        return $this->formatTree($allCategories);
    }

    // Get child IDs recursively
    public function getAllChildIds(int $parentId): array
    {
        $allCategories = ToolCategory::all(); 
        
        return $this->findChildIds($allCategories, $parentId);
    }

    // Format flat collection into nested tree
    protected function formatTree($categories, $parentId = null)
    {
        $branch = [];

        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $children = $this->formatTree($categories, $category->id);
                if ($children) {
                    $category->setRelation('children', collect($children));
                }
                $branch[] = $category;
            }
        }

        return collect($branch);
    }

    // Find children IDs in the collection
    protected function findChildIds($categories, $parentId): array
    {
        $ids = [];

        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $ids[] = $category->id;
                $ids = array_merge($ids, $this->findChildIds($categories, $category->id));
            }
        }

        return $ids;
    }
}
