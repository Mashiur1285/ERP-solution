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
        'boxes_sold',
        'bottles_per_box',
        'quantity',
        'free_bottles',
        'purchase_unit_price',
        'unit_price',
        'total_price',
        'quantity',
        'profit',
        'status',
        'delivery_date',
        'invoice_number',
        'status',
    ];

    protected $casts = [
        'delivery_date' => 'datetime',
        'purchase_unit_price' => 'decimal:2',
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
}
