<?php
// app/Providers/RepositoryServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\SupplierContract;
use App\Contracts\DepositContract;
use App\Contracts\ShopContract;
use App\Contracts\CategoryContract;
use App\Contracts\SalesItemContract;
use App\Contracts\BrandContract;
use App\Contracts\SalesContract;
use App\Contracts\ProductPurchaseContract;
use App\Repositories\ShopRepository;
use App\Repositories\SalesRepository;
use App\Repositories\BrandRepository;
use App\Repositories\ProductPurchaseRepository;
use App\Repositories\SalesItemRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\DepositRepository;
use App\Repositories\CategoryRepository;




class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SupplierContract::class, SupplierRepository::class);
        $this->app->bind(DepositContract::class, DepositRepository::class);
        $this->app->bind(ProductPurchaseContract::class, ProductPurchaseRepository::class);
        $this->app->bind(ShopContract::class, ShopRepository::class);
        $this->app->bind(CategoryContract::class, CategoryRepository::class);
        $this->app->bind(BrandContract::class, BrandRepository::class);
        $this->app->bind(SalesContract::class, SalesRepository::class);
        $this->app->bind(SalesItemContract::class, SalesItemRepository::class);
    }
}
