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
            'metadata',
            'date',
        ];
    protected $casts =
        [
            'metadata' => 'array',
            'date' => 'datetime'
        ];
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category():BelongsTo{

        return $this->belongsTo(Category::class);
    }
}
