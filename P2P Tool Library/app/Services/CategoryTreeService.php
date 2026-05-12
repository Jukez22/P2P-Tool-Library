<?php

namespace App\Services;

use App\Models\ToolCategory;

class CategoryTreeService
{

    public function buildTree()
    {
        $allCategories = ToolCategory::where('is_active', true)->get();

        return $this->formatTree($allCategories);
    }

    public function getAllChildIds(int $parentId): array
    {
        $allCategories = ToolCategory::all(); 

        return $this->findChildIds($allCategories, $parentId);
    }

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
