<?php
// app/Contracts/DepositContract.php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ExpenseContract extends BaseContract
{
    public function getExpenseReport(array $filters);
}
