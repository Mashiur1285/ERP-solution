<?php

namespace App\Contracts;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface ProductPurchaseContract
{
    public function purchaseHistory(): Collection;

    public function getInventoryStock(?string $snapshotDate = null): Collection;

    public function getProductsBySupplier(int $supplierId): Collection;

    public function updateInventory(Model $product, string $variant, int $soldPurchasedBottles, int $soldFreeBottles = 0): void;

    public function getVariantInventory(int $productId, string $variant): ?array;

    public function getTopSellingProducts(int $limit): Collection;

    public function getLowStockProducts(int $threshold): Collection;
}
