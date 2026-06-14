<?php

namespace App\Http\Controllers;

use App\Contracts\ExpenseContract;
use App\Contracts\SalesContract;
use App\Enums\SalesStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfitLossController extends Controller
{
    public function __construct(
        protected SalesContract $salesRepository,
        protected ExpenseContract $expenseRepository,
    ) {}

    public function index(Request $request)
    {
        $month = max(1, min(12, (int) $request->query('month', Carbon::now()->month)));
        $year  = max(2000, min(Carbon::now()->year + 1, (int) $request->query('year', Carbon::now()->year)));

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth   = Carbon::create($year, $month, 1)->endOfMonth();

        // Sales & items
        $sales    = $this->salesRepository->query()
            ->where('status', '!=', SalesStatus::DRAFT->value)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->with('items')
            ->get();

        $totalRevenue  = round($sales->sum('total_amount'), 2);
        $totalDiscount = round($sales->sum('discount'), 2);
        $netRevenue    = round($totalRevenue - $totalDiscount, 2);

        $allItems    = $sales->flatMap->items;
        $grossProfit = round($allItems->sum('profit'), 2);
        $totalCOGS   = round($netRevenue - $grossProfit, 2);

        $grossMargin = $netRevenue > 0 ? round(($grossProfit / $netRevenue) * 100, 2) : 0;

        // Expenses
        $expenseData   = $this->expenseRepository->getProfitLossExpenses(
            $startOfMonth->toDateTimeString(),
            $endOfMonth->toDateTimeString()
        );
        $totalExpenses = $expenseData['total'];
        $netProfit     = round($grossProfit - $totalExpenses, 2);
        $netMargin     = $netRevenue > 0 ? round(($netProfit / $netRevenue) * 100, 2) : 0;

        return Inertia::render('Reports/ProfitLoss', [
            'report' => [
                'revenue'            => $totalRevenue,
                'discount'           => $totalDiscount,
                'net_revenue'        => $netRevenue,
                'cogs'               => $totalCOGS,
                'gross_profit'       => $grossProfit,
                'gross_margin'       => $grossMargin,
                'expense_breakdown'  => $expenseData['breakdown'],
                'total_expenses'     => $totalExpenses,
                'net_profit'         => $netProfit,
                'net_margin'         => $netMargin,
            ],
            'month' => $month,
            'year'  => $year,
        ]);
    }
}
