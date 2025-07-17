<?php
// app/Repositories/DepositRepository.php

namespace App\Repositories;

use App\Models\Sale;
use App\Contracts\SalesContract;
use Illuminate\Database\Eloquent\Collection;

class SalesRepository extends BaseRepository implements SalesContract
{
    public function __construct(Sale $model)
    {
        parent::__construct($model);
    }
}
