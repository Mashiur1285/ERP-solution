<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = [
        'shop_name',
        'road',
        'owner_name',
        'shop_address',
        'phone_number',
        'email',
        'website',
        'national_id',
        'trade_license',
        'tax_id',
        'notes'
    ];

    protected $casts = [
        'shop_address' => 'string',
        'notes' => 'string',
    ];
}
