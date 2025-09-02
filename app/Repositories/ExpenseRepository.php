<?php

namespace App\Repositories;

use App\Contracts\ExpenseContract;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ExpenseRepository extends BaseRepository implements ExpenseContract
{
    public function __construct(Expense $model)
    {
        parent::__construct($model);
    }

    public function getExpenseReport(array $filters)
    {
        $query = Expense::query();

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [
                $filters['start_date'],
                $filters['end_date'],
            ]);
        }

        $summary = $query
            ->select('reason', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('reason')
            ->get()
            ->pluck('total_amount', 'reason')
            ->toArray();

        $total = array_sum($summary);
        $detailed = $query->get();

        return [
            'summary' => $summary,
            'total' => $total,
            'detailed' => $detailed,
        ];
    }


}
