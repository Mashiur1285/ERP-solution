<?php

namespace App\Http\Controllers;

use App\Contracts\SupplierContract;
use App\Contracts\DepositContract;
use App\Contracts\SalesContract;
use App\Contracts\ShopContract;
use App\Contracts\ProductPurchaseContract;
use App\Contracts\ExpenseContract; // Add this
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected SupplierContract $supplierRepository,
        protected DepositContract $depositRepository,
        protected SalesContract $salesRepository,
        protected ShopContract $shopRepository,
        protected ProductPurchaseContract $productPurchaseRepository,
        protected ExpenseContract $expenseRepository // Add this
    ) {
    }

    public function index(Request $request)
    {
        $suppliers = $this->supplierRepository->all();
        $shops = $this->shopRepository->getAllShopsWithDues();
        $topDeposits = $this->depositRepository
            ->totalRemainingDepositsBySupplier()
            ->take(5);

        // Fetch inventory stock
        $inventoryStock = $this->productPurchaseRepository->getInventoryStock();

        // Get month and year from query parameters, default to current month
        $month = $request->query('month', Carbon::now()->month);
        $year = $request->query('year', Carbon::now()->year);

        // Validate month and year
        $month = max(1, min(12, (int) $month));
        $year = max(2000, min(Carbon::now()->year + 1, (int) $year));

        // Calculate monthly sales metrics
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();

        $monthlySales = $this->salesRepository->query()
            ->whereBetween('sale_date', [$startOfMonth, $endOfMonth])
            ->with('items')
            ->get();

        $totalSales = $monthlySales->sum('total_amount');
        $totalPaid = $monthlySales->sum('paid_amount');
        $totalDue = $monthlySales->sum('due_amount');
        $totalProfit = $monthlySales->flatMap->items->sum(function ($item) {
            return $item->profit > 0 ? $item->profit : 0;
        });
        $totalLoss = $monthlySales->flatMap->items->sum(function ($item) {
            return $item->profit < 0 ? abs($item->profit) : 0;
        });

        // Calculate monthly expense metrics
        $monthlyExpenses = $this->expenseRepository->query()
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        $totalExpenses = $monthlyExpenses->count();
        $totalExpenseAmount = $monthlyExpenses->sum('amount');

        return Inertia::render('Dashboard/Dashboard', [
            'suppliers' => $suppliers,
            'shops' => $shops,
            'topDeposits' => $topDeposits,
            'inventoryStock' => $inventoryStock,
            'monthlySales' => [
                'total_sales' => $totalSales,
                'paid_amount' => $totalPaid,
                'due_amount' => $totalDue,
                'profit' => $totalProfit,
                'loss' => $totalLoss,
            ],
            'monthlyExpenses' => [
                'total_expenses' => $totalExpenses,
                'total_amount' => $totalExpenseAmount,
            ],
            'month' => $month,
            'year' => $year,
        ]);
    }
}
