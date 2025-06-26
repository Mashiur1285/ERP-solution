<?php

namespace App\Repositories;

use App\Contracts\SupplierContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class SupplierRepository extends BaseRepository implements SupplierContract
{
}