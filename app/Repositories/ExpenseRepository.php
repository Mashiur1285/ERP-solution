<?php

namespace App\Repositories;

use App\Contracts\ExpenseContract;
use App\Models\Expense;

class ExpenseRepository extends BaseRepository implements ExpenseContract
{
    public function __construct(Expense $model)
    {
        parent::__construct($model);
    }

    public function getExpenseReport(int $month, int $year): array
    {
        $start = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
        $end   = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();

        // Date by expense_date, falling back to created_at for rows entered
        // before the form had a date field. The dashboard counts them the same
        // way, so the two can no longer put an expense in different months.
        $expenses = Expense::whereRaw('COALESCE(expense_date, created_at::date) BETWEEN ? AND ?', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->orderByRaw('COALESCE(expense_date, created_at::date) DESC')
            ->orderByDesc('id')
            ->get();

        $summary = $expenses
            ->groupBy(fn($e) => $e->category ?: $e->reason)
            ->map(fn($group) => round($group->sum('amount'), 2))
            ->sortDesc()
            ->toArray();

        return [
            'summary'  => $summary,
            'total'    => array_sum($summary),
            'detailed' => $expenses,
        ];
    }

    public function getProfitLossExpenses(string $startDate, string $endDate): array
    {
        $expenses = Expense::whereBetween('created_at', [$startDate, $endDate])->get();

        $breakdown = $expenses
            ->groupBy(fn($e) => $e->category ?: $e->reason)
            ->map(fn($group, $key) => [
                'name' => $key,
                'amount' => round($group->sum('amount'), 2),
                'count' => $group->count(),
            ])
            ->values()
            ->toArray();

        return [
            'breakdown' => $breakdown,
            'total' => round($expenses->sum('amount'), 2),
        ];
    }


}
