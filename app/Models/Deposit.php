<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposit extends Model
{
        protected $fillable = [
         'balance_deposited',
         'balance_remaining',
         'deposit_date',
         'is_used'
    ];
    

     protected $cast=[
         'is_used' => 'boolean',
     ];


     public function supplier (): BelongsTo
     {
        return $this->belongsTo(Supplier::class);
     }

     

}
