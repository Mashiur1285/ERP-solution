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
                'deposits.id',
                'deposits.supplier_id',
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
        $deposit->balance_remaining = round((float) $deposit->balance_remaining - $purchaseAmount, 2);
        $deposit->balance_used = round((float) $deposit->balance_deposited - (float) $deposit->balance_remaining, 2);
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

    public function applyAmountAgainstSupplierDeposits(int $supplierId, float $amount): void
    {
        $remainingAmount = round($amount, 2);

        if ($remainingAmount <= 0) {
            return;
        }

        $deposits = $this->model
            ->where('supplier_id', $supplierId)
            ->where('balance_remaining', '>', 0)
            ->orderBy('deposit_date', 'desc')
            ->lockForUpdate()
            ->get();

        foreach ($deposits as $deposit) {
            if ($remainingAmount <= 0) {
                break;
            }

            $available = (float) $deposit->balance_remaining;
            $usedNow = min($available, $remainingAmount);

            $deposit->balance_remaining = round($available - $usedNow, 2);
            $deposit->balance_used = round(((float) ($deposit->balance_used ?? 0)) + $usedNow, 2);
            $deposit->is_used = $deposit->balance_remaining <= 0;
            $deposit->save();

            $remainingAmount = round($remainingAmount - $usedNow, 2);
        }

        // Whatever the deposits could not cover is money the supplier is still owed.
        // The lift really happened, so refusing it left the books behind reality;
        // instead the balance goes negative and the next deposit absorbs it.
        if ($remainingAmount > 0) {
            $carry = $this->model
                ->where('supplier_id', $supplierId)
                ->orderBy('deposit_date', 'desc')
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();

            if ($carry) {
                $carry->balance_remaining = round(((float) $carry->balance_remaining) - $remainingAmount, 2);
                $carry->balance_used      = round(((float) ($carry->balance_used ?? 0)) + $remainingAmount, 2);
                $carry->is_used           = $carry->balance_remaining <= 0;
                $carry->save();

                return;
            }

            // No deposit on file at all: open one that records only the debt.
            $this->model->create([
                'supplier_id'       => $supplierId,
                'balance_deposited' => 0,
                'balance_used'      => $remainingAmount,
                'balance_remaining' => -$remainingAmount,
                'deposit_date'      => now()->toDateString(),
                'is_used'           => true,
            ]);
        }
    }

    public function creditAmountBackToSupplierDeposits(int $supplierId, float $amount): void
    {
        $remainingAmount = round($amount, 2);

        if ($remainingAmount <= 0) {
            return;
        }

        $deposits = $this->model
            ->where('supplier_id', $supplierId)
            ->where('balance_used', '>', 0)
            ->orderBy('deposit_date', 'desc')
            ->lockForUpdate()
            ->get();

        // Never credit back more than was actually drawn from the deposits.
        // A lift's total_amount can legitimately diverge from the recorded
        // usage after an inventory stock adjustment, so cap the credit at the
        // amount truly used instead of throwing. On a lift re-save the new
        // total is re-applied afterwards, which keeps the ledger consistent.
        $totalUsed = round((float) $deposits->sum('balance_used'), 2);
        $remainingAmount = min($remainingAmount, $totalUsed);

        foreach ($deposits as $deposit) {
            if ($remainingAmount <= 0) {
                break;
            }

            $usedAmount = (float) ($deposit->balance_used ?? 0);
            if ($usedAmount <= 0) {
                continue;
            }

            $creditNow = min($usedAmount, $remainingAmount);

            $deposit->balance_used = round($usedAmount - $creditNow, 2);
            $deposit->balance_remaining = round(((float) $deposit->balance_remaining) + $creditNow, 2);
            $deposit->is_used = $deposit->balance_remaining <= 0;
            $deposit->save();

            $remainingAmount = round($remainingAmount - $creditNow, 2);
        }
    }
}
