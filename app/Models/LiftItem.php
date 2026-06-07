<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiftItem extends Model
{
    protected $fillable = [
        'lift_id',
        'product_catalog_id',
        'product_id',
        'variant',
        'number_of_cases',
        'case_buying_price',
        'bottles_per_case',
        'free_bottles_per_case',
        'total_bottles',
        'total_free_bottles',
        'total_cost',
        'actual_rate_per_bottle',
        'cases_with_free_bottles',
        'cases_without_free_bottles',
        'extra_free_bottles',
    ];

    protected $casts = [
        'case_buying_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'actual_rate_per_bottle' => 'decimal:4',
    ];

    public function lift(): BelongsTo
    {
        return $this->belongsTo(Lift::class);
    }

    public function productCatalog(): BelongsTo
    {
        return $this->belongsTo(ProductCatalog::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
