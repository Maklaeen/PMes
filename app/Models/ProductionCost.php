<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionCost extends Model
{
    protected $fillable = [
        'production_schedule_id',
        'material_cost',
        'labor_cost',
        'total_cost',
        'cost_per_unit',
        'computed_by_user_id',
        'computed_at',
    ];

    protected $casts = [
        'computed_at' => 'datetime',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(ProductionSchedule::class, 'production_schedule_id');
    }

    public function computedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'computed_by_user_id');
    }
}
