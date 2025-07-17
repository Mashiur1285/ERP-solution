<?php
// app/Repositories/DepositRepository.php

namespace App\Repositories;

use App\Models\Brand;
use App\Contracts\BrandContract;
use Illuminate\Database\Eloquent\Collection;

class BrandRepository extends BaseRepository implements BrandContract
{
    public function __construct(Brand $model)
    {
        parent::__construct($model);
    }
}
