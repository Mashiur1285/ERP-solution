<?php
// app/Contracts/DepositContract.php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\Deposit;

interface DepositContract extends BaseContract
{
    public function depositHistory(): Collection;
    public function updateDepositTable(int $purchaseAmount, Model $deposit): void;
    public function findTheLatestDepositForTheSupplier(int $supplierId): ?Deposit;
    public function totalRemainingDepositsBySupplier(): Collection;
    public function applyAmountAgainstSupplierDeposits(int $supplierId, float $amount): void;
    public function creditAmountBackToSupplierDeposits(int $supplierId, float $amount): void;
}
