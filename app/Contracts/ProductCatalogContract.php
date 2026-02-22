<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ProductCatalogContract extends BaseContract
{
    public function getBySupplier(int $supplierId): Collection;

    public function searchBySupplier(int $supplierId, string $search = ''): Collection;
}
