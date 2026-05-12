<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(ToolCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ToolCategory::class, 'parent_id');
    }

    public function tools()
    {
        return $this->belongsToMany(Tool::class, 'tool_category_mappings', 'tool_category_id', 'tool_id');
    }
}
