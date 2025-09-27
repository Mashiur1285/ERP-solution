<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface SalesContract
{
    public function updateSales(array $data, int $id): Model;
}
