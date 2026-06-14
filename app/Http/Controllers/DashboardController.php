<?php

namespace App\Http\Controllers;

use App\Contracts\SupplierContract;
use App\Contracts\DepositContract;
use App\Contracts\SalesContract;
use App\Contracts\ShopContract;
use App\Contracts\ProductPurchaseContract;
use App\Contracts\ExpenseContract; // Add this
use App\Contracts\LiftContract;
use App\Enums\SalesStatus;
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
        protected ExpenseContract $expenseRepository, // Add this
        protected LiftContract $liftRepository
    ) {
    }

    public function index(Request $request)
    {
        $shopDuesDate = $request->query('shop_dues_date', Carbon::now()->toDateString());
        $dailySalesDate = $request->query('daily_sales_date', Carbon::now()->toDateString());

        // Get date range for the graph (default last 6 days including today)
        $graphEndDate = $request->query('graph_end_date', Carbon::now()->toDateString());
        $graphStartDate = $request->query('graph_start_date', Carbon::now()->subDays(5)->toDateString());

        $suppliers = $this->supplierRepository->all();
        $shops = $this->shopRepository->getAllShopsWithDues($shopDuesDate);
        $dateWiseSalesData = $this->shopRepository->getDateWiseSalesData($graphStartDate, $graphEndDate);
        $depositSummary = $this->depositRepository->totalRemainingDepositsBySupplier();
        $topDeposits = $depositSummary->take(4)->values();
        $totalDepositAmount = $depositSummary->sum('total_remaining_deposit');

        // Fetch inventory stock
        $inventoryStock = $this->productPurchaseRepository->getInventoryStock();
        $topSellingProducts = $this->productPurchaseRepository->getTopSellingProducts(5);
        $lowStockProducts = $this->productPurchaseRepository->getLowStockProducts(10);
        $todaysLiftingCount = $this->productPurchaseRepository->query()
            ->whereDate('date', $dailySalesDate)
            ->count();

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
            ->where('status', '!=', SalesStatus::DRAFT->value)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
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
        $grossProfit = round($totalProfit - $totalLoss, 2);

        // Calculate monthly expense metrics
        $monthlyExpenses = $this->expenseRepository->query()
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        $totalExpenses = $monthlyExpenses->count();
        $totalExpenseAmount = $monthlyExpenses->sum('amount');

        $sales = $this->salesRepository->query()
            ->where('status', '!=', SalesStatus::DRAFT->value)
            ->whereDate('created_at', $dailySalesDate)
            ->with('items', 'shop')
            ->get();

        $todaysExpensesAmount = $this->expenseRepository->query()
            ->whereDate('created_at', $dailySalesDate)
            ->sum('amount');

        $lifts = $this->liftRepository->query()
            ->where('status', 'completed')
            ->whereBetween('lift_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->with(['supplier', 'items.productCatalog'])
            ->orderByDesc('lift_date')
            ->orderByDesc('id')
            ->get();

        $totalShopsSold = $sales->pluck('shop_id')->unique()->count();
        $totalCasesSold = $sales->flatMap->items->sum('cases_sold');
        $totalLiftCases = $lifts->flatMap->items->sum('number_of_cases');
        $totalLiftBottles = $lifts->flatMap->items->sum('total_bottles');
        $totalLiftAmount = $lifts->sum('total_amount');
        $totalFreeLiftBottles = $lifts->flatMap->items->sum('total_free_bottles');


        return Inertia::render('Dashboard/Dashboard', [
            'suppliers' => $suppliers,
            'shops' => $shops,
            'dateWiseSalesData' => $dateWiseSalesData,
            'topDeposits' => $topDeposits,
            'totalDepositAmount' => $totalDepositAmount,
            'inventoryStock' => $inventoryStock,
            'topSellingProducts' => $topSellingProducts,
            'lowStockProducts' => $lowStockProducts,
            'monthlySales' => [
                'total_sales'   => $totalSales,
                'paid_amount'   => $totalPaid,
                'due_amount'    => $totalDue,
                'profit'        => $totalProfit,
                'loss'          => $totalLoss,
                'gross_profit'  => $grossProfit,
                'net_profit'    => round($grossProfit - $totalExpenseAmount, 2),
            ],
            'monthlyExpenses' => [
                'total_expenses' => $totalExpenses,
                'total_amount' => $totalExpenseAmount,
            ],
            'month' => $month,
            'year' => $year,
            'sales' => $sales,
            'lifts' => $lifts,
            'totalShopsSold' => $totalShopsSold,
            'totalCasesSold' => $totalCasesSold,
            'totalLiftCases' => $totalLiftCases,
            'totalLiftBottles' => $totalLiftBottles,
            'totalLiftAmount' => $totalLiftAmount,
            'totalFreeLiftBottles' => $totalFreeLiftBottles,
            'todaysExpensesAmount' => $todaysExpensesAmount,
            'todaysLiftingCount' => $todaysLiftingCount,
        ]);
    }
}
