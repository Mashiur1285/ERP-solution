<?php
// app/Repositories/DepositRepository.php

namespace App\Repositories;

use App\Models\Deposit;
use App\Contracts\DepositContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DepositRepository extends BaseRepository implements DepositContract
{
    public function __construct(Deposit $model)
    {
        parent::__construct($model);
    }

    /**
     * Retrieve the deposit history with relevant supplier details.
     *
     * This method joins the deposits table with the suppliers table to fetch
     * the supplier's company name, deposit amount, remaining balance, and deposit date.
     * Results are ordered by deposit date in descending order.
     *
     * @return Collection A collection of deposit history records
     */
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

    /**
     * update the balance_remaining field with new purchase amount
     */
    public function updateDepositTable(int $purchaseAmount, Model $deposit): void
    {
        $deposit->balance_remaining -= $purchaseAmount;
        $deposit->is_used = true;
        $deposit->save();
    }

    public function findTheLatestDepositForTheSupplier(int $supplierId): Deposit
    {
        return $this->model
            ->where('supplier_id', operator: $supplierId)
            ->where('balance_remaining', '>', 0)
            ->orderBy('deposit_date', 'desc')
            ->first();
    }

    public function totalRemainingDepositsBySupplier(): Collection
    {
        return $this->model
            ->select(
                'suppliers.company_name as supplier_name',
                \DB::raw('SUM(deposits.balance_remaining) as total_remaining_deposit')
            )
            ->join('suppliers', 'deposits.supplier_id', 'suppliers.id')
            ->groupBy('suppliers.company_name')
            ->orderBy('suppliers.company_name')
            ->get();
    }
}
