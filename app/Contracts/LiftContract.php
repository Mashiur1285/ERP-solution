<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface LiftContract extends BaseContract
{
    public function saveLiftWithItems(array $liftData, array $items, int $supplierId, ?int $liftId = null): Model;

    public function liftHistory(?int $supplierId = null): Collection;
}
