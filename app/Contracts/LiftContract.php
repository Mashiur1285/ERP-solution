<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface LiftContract extends BaseContract
{
    public function createLiftWithItems(array $liftData, array $items, int $supplierId): Model;

    public function liftHistory(?int $supplierId = null): Collection;
}
