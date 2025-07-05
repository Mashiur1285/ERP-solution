<?php
// app/Repositories/DepositRepository.php

namespace App\Repositories;

use App\Models\Product;
use App\Contracts\ProductPurchaseContract;
use Illuminate\Database\Eloquent\Collection;

class ProductPurchaseRepository extends BaseRepository implements ProductPurchaseContract
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }
}
