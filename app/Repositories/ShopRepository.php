<?php

namespace App\Repositories;

use App\Models\Shop;
use App\Contracts\ShopContract;
use Illuminate\Support\Facades\DB;

class ShopRepository extends BaseRepository implements ShopContract
{
    public function __construct(Shop $model)
    {
        parent::__construct($model);
    }

    public function getAllShopsWithDues($date)
    {
        return $this->model
            ->leftJoin('sales', function ($join) use ($date) {
                $join->on('shops.id', '=', 'sales.shop_id')
                    ->whereRaw('DATE(sales.created_at) = ?', [$date])
                    ->where('sales.status', '!=', 'draft');
            })
            ->select(
                'shops.id as shop_id',
                'shops.shop_name',
                'shops.owner_name',
                'shops.phone_number',
                DB::raw('COALESCE(SUM(sales.paid_amount), 0) as total_paid'),
                DB::raw('COALESCE(SUM(sales.due_amount), 0) as total_due')
            )
            ->groupBy('shops.id', 'shops.shop_name', 'shops.owner_name', 'shops.phone_number')
            ->orderBy('total_due', 'desc')
            ->get();
    }

    public function getDateWiseSalesData($startDate = null, $endDate = null)
    {
        // Set default date range if not provided (last 6 days including today)
        if (!$startDate || !$endDate) {
            $endDate = \Carbon\Carbon::now()->format('Y-m-d');
            $startDate = \Carbon\Carbon::now()->subDays(5)->format('Y-m-d');
        }

        // Get sales data for the date range using created_at instead of sale_date
        $salesData = DB::table('sales')
            ->whereRaw('DATE(created_at) BETWEEN ? AND ?', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as sale_date'),
                DB::raw('COALESCE(SUM(paid_amount), 0) as total_paid'),
                DB::raw('COALESCE(SUM(due_amount), 0) as total_due'),
                DB::raw('COALESCE(SUM(total_amount), 0) as total_amount'),
                DB::raw('COUNT(DISTINCT shop_id) as shop_count')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'), 'asc')
            ->get()
            ->keyBy('sale_date');

        // Generate all dates in the range and fill missing dates with zeros
        $result = [];
        $currentDate = \Carbon\Carbon::parse($startDate);
        $endDateObj = \Carbon\Carbon::parse($endDate);

        while ($currentDate->lte($endDateObj)) {
            $dateString = $currentDate->format('Y-m-d');

            if (isset($salesData[$dateString])) {
                $result[] = $salesData[$dateString];
            } else {
                $result[] = (object)[
                    'sale_date' => $dateString,
                    'total_paid' => 0,
                    'total_due' => 0,
                    'total_amount' => 0,
                    'shop_count' => 0
                ];
            }

            $currentDate->addDay();
        }

        return collect($result);
    }
}
