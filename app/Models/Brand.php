<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['brand_name', 'description'];

    /**
     * Get the products associated with the brand.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the categories associated with the brand.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
