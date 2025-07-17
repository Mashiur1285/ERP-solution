<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'free_bottles',
        'purchase_unit_price',
        'unit_price',
        'total_price',
        'quantity',
        'profit',
        'status',
        'delivery_date',
    ];

    protected $casts = [
        'delivery_date' => 'datetime',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsToMany
    {
        return $this->blongsTo(Product::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
