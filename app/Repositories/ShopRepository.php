<?php

namespace App\Repositories;

use App\Models\Shop;
use App\Contracts\ShopContract;

class ShopRepository extends BaseRepository implements ShopContract
{
    public function __construct(Shop $model)
    {
        parent::__construct($model);
    }
}
