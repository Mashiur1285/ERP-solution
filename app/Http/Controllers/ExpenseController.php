<?php

namespace App\Http\Controllers;

use App\Contracts\ExpenseContract;
use App\Models\Expense;
use App\Http\Requests\ExpenseRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpenseController extends Controller
{

    public function __construct(
        protected ExpenseContract $expenseRepository,
    ) {
    }

    function index()
    {
        $expenses = $this->expenseRepository->all();
        return Inertia::render('ExpenseManagement/Expense', [
            'expenses' => $expenses
        ]);
    }

    function storeExpense(ExpenseRequest $request)
    {
        $validated = $request->validated();
        $expense = $this->expenseRepository->create($validated);
    }

    function update(ExpenseRequest $request, $id)
    {
        $validated = $request->validated();
        $expense = $this->expenseRepository->update($validated, $id);
    }

    public function report(Request $request)
    {
        $month = max(1, min(12, (int) $request->query('month', now()->month)));
        $year  = max(2000, min(now()->year + 1, (int) $request->query('year', now()->year)));

        $report = $this->expenseRepository->getExpenseReport($month, $year);

        return Inertia::render('ExpenseManagement/Report', [
            'report' => $report,
            'month'  => $month,
            'year'   => $year,
        ]);
    }
}
