<?php
// app/Repositories/DepositRepository.php

namespace App\Repositories;

use App\Models\Supplier; // Make sure you have this model
use App\Contracts\SupplierContract;

class DepositRepository extends BaseRepository implements DepositContract
{
    public function __construct(Supplier $model)
    {
        parent::__construct($model);
    }
}