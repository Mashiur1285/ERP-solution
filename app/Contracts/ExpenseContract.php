<?php
// app/Contracts/DepositContract.php

namespace App\Contracts;

interface ExpenseContract extends BaseContract
{
    public function getExpenseReport(int $month, int $year): array;
    public function getProfitLossExpenses(string $startDate, string $endDate): array;
}
