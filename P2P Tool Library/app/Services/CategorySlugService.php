<?php

namespace App\Services;

use App\Models\ToolCategory;
use Illuminate\Support\Str;

// Handle unique slugs for categories
class CategorySlugService
{
    // Generate unique slug from name
    public function generateSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (ToolCategory::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
