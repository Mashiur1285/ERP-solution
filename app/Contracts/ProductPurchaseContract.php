<?php

namespace App\Contracts;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface ProductPurchaseContract
{
    public function purchaseHistory(): Collection;

    public function getInventoryStock(): Collection;

    public function getProductsBySupplier(int $supplierId): Collection;

    public function updateInventory(Model $product, string $variant, int $soldPurchasedBottles, int $soldFreeBottles = 0): void;

    public function getVariantInventory(int $productId, string $variant): ?array;
}
