<?php

namespace App\Rules;

use App\Models\ToolCategory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoCircularCategoryHierarchy implements ValidationRule
{
    protected $categoryId;

    public function __construct($categoryId = null)
    {
        $this->categoryId = $categoryId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->categoryId || !$value) {
            return;
        }

        if ((int) $value === (int) $this->categoryId) {
            $fail('A category cannot be its own parent.');
            return;
        }

        if ($this->isDescendant($this->categoryId, $value)) {
            $fail('Circular category hierarchy detected. The selected parent is a descendant of this category.');
        }
    }

    protected function isDescendant($currentId, $potentialParentId): bool
    {
        $children = ToolCategory::where('parent_id', $currentId)->pluck('id')->toArray();

        if (in_array($potentialParentId, $children)) {
            return true;
        }

        foreach ($children as $childId) {
            if ($this->isDescendant($childId, $potentialParentId)) {
                return true;
            }
        }

        return false;
    }
}
