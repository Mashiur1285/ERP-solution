<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'supplier_id',
        'invoice_number',
        'variant',
        'cases_sold',
        'bottles_per_case',
        'purchased_bottles_sold',
        'free_bottles_sold',
        'total_bottles_sold',
        'purchase_unit_price',
        'selling_price_per_case',
        'unit_price',
        'total_price',
        'profit',
        'status',
        'delivery_date',
    ];

    protected $casts = [
        'delivery_date' => 'datetime',
        'purchase_unit_price' => 'decimal:2',
        'selling_price_per_case' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'profit' => 'decimal:2',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
