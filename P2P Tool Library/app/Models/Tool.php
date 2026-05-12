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
        'usage_count',
        'maintenance_interval_uses',
        'needs_inspection',
        'safety_cert_expiry_date',
        'warranty_expiry_date',
        'is_unfit',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_boosted' => 'boolean',
        'location_lng' => 'decimal:7',
        'location_lat' => 'decimal:7',
        'needs_inspection' => 'boolean',
        'is_unfit' => 'boolean',
        'safety_cert_expiry_date' => 'date',
        'warranty_expiry_date' => 'date',
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

    public function externalRepairs()
    {
        return $this->hasMany(ExternalRepair::class);
    }

    public function batteryHealthLogs()
    {
        return $this->hasMany(BatteryHealthLog::class);
    }

    public function disposal()
    {
        return $this->hasOne(Disposal::class);
    }

    public function sparePartOrders()
    {
        return $this->hasMany(SparePartOrder::class);
    }
}
