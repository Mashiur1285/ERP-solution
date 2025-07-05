<?php
// app/Contracts/DepositContract.php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface DepositContract extends BaseContract
{
    // Add any Deposit-specific methods here
    public function depositHistory(): Collection;
}
