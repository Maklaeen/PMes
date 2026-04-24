<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductionSchedule extends Model
{
    protected $fillable = [
        'product_id',
        'planned_quantity',
        'schedule_date',
        'status',
        'created_by_user_id',
        'started_at',
        'expected_end_at',
        'completed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'started_at' => 'datetime',
        'expected_end_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function qualityChecks(): HasMany
    {
        return $this->hasMany(QualityCheck::class);
    }

    public function cost(): HasOne
    {
        return $this->hasOne(ProductionCost::class);
    }

    public function materialMovements(): HasMany
    {
        return $this->hasMany(MaterialMovement::class);
    }
}
