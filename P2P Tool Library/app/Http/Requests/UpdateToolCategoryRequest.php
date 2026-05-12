<?php

namespace App\Http\Requests;

use App\Rules\NoCircularCategoryHierarchy;
use Illuminate\Foundation\Http\FormRequest;

class UpdateToolCategoryRequest extends FormRequest
{
    // Authorization
    public function authorize(): bool
    {
        return true;
    }

    // Validation rules
    public function rules(): array
    {
        $categoryId = $this->route('categoryId');

        return [
            'name'        => 'sometimes|string|unique:tool_categories,name,' . $categoryId,
            'slug'        => 'sometimes|string|unique:tool_categories,slug,' . $categoryId,
            'parent_id'   => [
                'nullable',
                'exists:tool_categories,id',
                new NoCircularCategoryHierarchy($categoryId) // Custom loop check
            ],
            'description' => 'nullable|string',
            'icon'        => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ];
    }
}
