<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
        protected $fillable = [
        'company_name',
        'branch_name',
        'phone_number',
        'emergency_phone_number',
        'address',
        'email',
        'country',
        'city',
        'website',
        'notes',
    ];

    protected $casts = [
        'address' => 'string',
        'notes'   => 'string',
    ];
}
