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


    public function depositHistory(): Collection
    {
        return $this->model
            ->select(
                'suppliers.company_name as name',
                'suppliers.phone_number as phone_number',
                'deposits.balance_deposited as deposit_amount',
                'deposits.balance_remaining as remaining_amount',
                'deposits.balance_used as balance_used',
                'deposits.deposit_date as date'
            )
            ->join('suppliers', 'deposits.supplier_id', 'suppliers.id')
            ->orderBy('deposits.deposit_date', 'desc') // Only this needed
            ->get();
    }


    /**
     * update the balance_remaining field with new purchase amount
     */
    public function updateDepositTable(int $purchaseAmount, Model $deposit): void
    {
        $deposit->balance_remaining -= $purchaseAmount;
        $deposit->balance_used = $deposit->balance_deposited - $deposit->balance_remaining;
        $deposit->is_used = $deposit->balance_remaining <= 0;
        $deposit->save();
    }

    public function findTheLatestDepositForTheSupplier(int $supplierId): ?Deposit
    {
        return $this->model
            ->where('supplier_id', $supplierId)
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
            ->orderBy(\DB::raw('SUM(deposits.balance_remaining)'), 'desc') // Order by total_remaining_deposit descending
            ->get();
    }
}
