<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'shop_id',
        'invoice_number',
        'total_amount',
        'subtotal',
        'discount',
        'tax',
        'shipping_cost',
        'paid_amount',
        'is_paid',
        'due_amount',
        'status',
        'sale_date',
    ];
    protected $casts = [
        'sale_date' => 'datetime',
        'is_paid' => 'boolean',
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];
}
