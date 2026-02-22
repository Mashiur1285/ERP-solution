<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCatalog extends Model
{
    protected $table = 'product_catalog';

    protected $fillable = [
        'name',
        'supplier_id',
        'category_id',
        'brand_id',
        'default_variants',
        'is_active',
    ];

    protected $casts = [
        'default_variants' => 'array',
        'is_active' => 'boolean',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function liftItems(): HasMany
    {
        return $this->hasMany(LiftItem::class);
    }
}
