<?php
// app/Repositories/DepositRepository.php

namespace App\Repositories;

use App\Models\SaleItem;
use App\Contracts\SalesItemContract;
use Illuminate\Database\Eloquent\Collection;

class SalesItemRepository extends BaseRepository implements SalesItemContract
{
    public function __construct(SaleItem $model)
    {
        parent::__construct($model);
    }
}
