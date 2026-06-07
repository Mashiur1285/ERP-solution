<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable =
        [
            'name',
            'supplier_id',
            'category_id',
            'brand_id',
            'product_catalog_id',
            'metadata',
            'date',
        ];
    protected $casts =
        [
            'metadata' => 'array',
            // Purchase date is a calendar date; date-only cast avoids the UTC
            // timestamp serialization that shifts the day for +06 timezones.
            'date' => 'date:Y-m-d'
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

    public function productCatalog(): BelongsTo
    {
        return $this->belongsTo(ProductCatalog::class);
    }
}
