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
        'lift_date' => 'datetime',
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
