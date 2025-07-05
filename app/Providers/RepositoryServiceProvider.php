<?php
// app/Providers/RepositoryServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\SupplierContract;
use App\Contracts\DepositContract;
use App\Contracts\CategoryContract;
use App\Contracts\ProductPurchaseContract;
use App\Repositories\ProductPurchaseRepository;
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
        $this->app->bind(CategoryContract::class,CategoryRepository::class);
    }
}
