<?php

namespace App\Repositories;

use App\Models\Shop;
use App\Contracts\ShopContract;
use Illuminate\Support\Facades\DB;

class ShopRepository extends BaseRepository implements ShopContract
{
    public function __construct(Shop $model)
    {
        parent::__construct($model);
    }

    public function getAllShopsWithDues()
    {
        return DB::table('shop_dues_summary')
            ->orderBy('total_due', 'desc')
            ->get();
    }
}
