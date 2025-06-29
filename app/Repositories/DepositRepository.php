<?php
// app/Repositories/DepositRepository.php

namespace App\Repositories;

use App\Models\Supplier; // Make sure you have this model
use App\Contracts\SupplierContract;

class DepositRepository extends BaseRepository implements DepositContract
{
    public function __construct(Supplier $model)
    {
        parent::__construct($model);
    }

    public function depositHistory()
    {
        return $this->model
        ->select('suppliers.company_name as name',
                 'deposits.balance_deposited as deposit amount',
                 'deposits.balance_remaining as remaining_amount',
                 'deposits.deposit_date as date'
         ) 
         ->join('suppliers', 'deposits.supplier_id', 'suppliers_id')
         ->orderBy('deposits.deposit_date', 'desc')
         ->get();
    }
}