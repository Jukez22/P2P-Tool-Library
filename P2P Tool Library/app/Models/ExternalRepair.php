<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalRepair extends Model
{
    protected $fillable = [
        'tool_id',
        'maintenance_log_id',
        'shop_name',
        'dispatch_date',
        'expected_return_date',
        'status',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function maintenanceLog()
    {
        return $this->belongsTo(MaintenanceLog::class);
    }
}
