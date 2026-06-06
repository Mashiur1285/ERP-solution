<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lift extends Model
{
    protected $fillable = [
        'supplier_id',
        'lift_number',
        'status',
        'total_amount',
        'lift_date',
        'notes',
    ];

    protected $casts = [
        // Calendar date only — a 'datetime' cast serializes to a UTC timestamp
        // (e.g. 2026-06-05T18:00:00Z) which shifts the day for +06 users and makes
        // lifts drop out of date-range filters. 'date:Y-m-d' serializes as a plain
        // 'Y-m-d' string with no time/timezone.
        'lift_date' => 'date:Y-m-d',
        'total_amount' => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(LiftItem::class);
    }
}
