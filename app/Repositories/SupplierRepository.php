<?php
// app/Repositories/SupplierRepository.php

namespace App\Repositories;

use App\Models\Supplier; // Make sure you have this model
use App\Contracts\SupplierContract;

class SupplierRepository extends BaseRepository implements SupplierContract
{
    public function __construct(Supplier $model)
    {
        parent::__construct($model);
    }
}