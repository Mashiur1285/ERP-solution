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
        $filters = $request->only(['start_date', 'end_date']);
        $report = $this->expenseRepository->getExpenseReport($filters);
        return Inertia::render('ExpenseManagement/Report', [
            'report' => $report,
            'filters' => $filters,
        ]);
    }
}
