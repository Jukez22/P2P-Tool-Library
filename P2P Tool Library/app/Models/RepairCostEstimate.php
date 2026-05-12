<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairCostEstimate extends Model
{
    protected $fillable = [
        'issue_name',
        'estimated_cost',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
