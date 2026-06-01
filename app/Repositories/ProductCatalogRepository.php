<?php

namespace App\Repositories;

use App\Models\ProductCatalog;
use App\Contracts\ProductCatalogContract;
use Illuminate\Database\Eloquent\Collection;

class ProductCatalogRepository extends BaseRepository implements ProductCatalogContract
{
    public function __construct(ProductCatalog $model)
    {
        parent::__construct($model);
    }

    public function getBySupplier(int $supplierId): Collection
    {
        return $this->model
            ->where('supplier_id', $supplierId)
            ->where('is_active', true)
            ->with(['category', 'brand'])
            ->orderBy('name')
            ->get();
    }

    public function searchBySupplier(int $supplierId, string $search = ''): Collection
    {
        return $this->model
            ->where('supplier_id', $supplierId)
            ->where('is_active', true)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'ilike', '%' . $search . '%');
            })
            ->with(['category', 'brand'])
            ->orderBy('name')
            ->limit(20)
            ->get();
    }
}
