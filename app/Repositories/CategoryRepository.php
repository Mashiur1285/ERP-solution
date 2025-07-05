<?php
// app/Repositories/DepositRepository.php

namespace App\Repositories;

use App\Models\Deposit;
use App\Contracts\CategoryContract;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository implements CategoryContract
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

   
}
