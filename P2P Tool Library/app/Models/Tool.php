<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    protected $fillable = [
        'title',
        'price',
        'description',
        'condition_status',
        'is_boosted',
        'location_lng',
        'location_lat',
        'category_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_boosted' => 'boolean',
        'location_lng' => 'decimal:7',
        'location_lat' => 'decimal:7',
    ];

    const UPDATED_AT = null;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function availability()
    {
        return $this->hasMany(ToolAvailability::class);
    }

    public function documents()
    {
        return $this->hasMany(ToolDocument::class);
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class);
    }
}
