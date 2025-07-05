<?php
// app/Repositories/DepositRepository.php

namespace App\Repositories;

use App\Models\Deposit;
use App\Contracts\DepositContract;
use Illuminate\Database\Eloquent\Collection;

class DepositRepository extends BaseRepository implements DepositContract
{
    public function __construct(Deposit $model)
    {
        parent::__construct($model);
    }

    public function depositHistory(): Collection
    {
        return $this->model
            ->select(
                'suppliers.company_name as name',
                'deposits.balance_deposited as deposit_amount',
                'deposits.balance_remaining as remaining_amount',
                'deposits.deposit_date as date'
            )
            ->join('suppliers', 'deposits.supplier_id', 'suppliers.id')
            ->orderBy('deposits.deposit_date', 'desc')
            ->get();
    }
}
