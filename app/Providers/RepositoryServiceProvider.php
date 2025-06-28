<?php
// app/Providers/RepositoryServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\SupplierContract;
use App\Contracts\DepositContract;
use App\Repositories\SupplierRepository;
use App\Repositories\DepositRepository;


class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SupplierContract::class, SupplierRepository::class);
        $this->app->bind(DepositContract::class, DepositRepository::class);
    }
}