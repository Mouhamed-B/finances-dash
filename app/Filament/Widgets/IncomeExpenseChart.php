<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use App\Models\Income;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class IncomeExpenseChart extends ApexChartWidget
{
    protected static ?string $heading = 'Income, Expenses, and Balance over time';

    protected int | string | array $columnSpan = [
        'sm' => 2,
        'md' => 6,
    ];

    protected function getData(): array
    {
        $userId = Auth::id();
        $thirtyDaysAgo = now()->subDays(30);

        // Fetch income data using Eloquent
        $incomeData = Income::where('user_id', $userId)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // Fetch expense data using Eloquent
        $expenseData = Expense::where('user_id', $userId)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // Merge both income and expense data
        $dates = array_unique(array_merge(array_keys($incomeData->toArray()), array_keys($expenseData->toArray())));

        // Prepare the data for the chart
        $incomeValues = [];
        $expenseValues = [];
        $balanceValues = [];
        foreach ($dates as $date) {
            $income = $incomeData[$date] ?? 0;
            $expense = $expenseData[$date] ?? 0;
            $balance = $income - $expense;

            $incomeValues[] = $income;
            $expenseValues[] = $expense;
            $balanceValues[] = $balance;
        }

        return [
            'series' => [
                [
                    'name' => 'Income',
                    'data' => $incomeValues,
                ],
                [
                    'name' => 'Expenses',
                    'data' => $expenseValues,
                ],
                [
                    'name' => 'Balance',
                    'data' => $balanceValues,
                ],
            ],
            'chart' => [
                'type' => 'line',
                'height' => 350,
            ],
            'stroke' => [
                'curve' => 'smooth', // Smooth lines
            ],
            'xaxis' => [
                'type' => 'category',
                'categories' => $dates,
            ],
            'yaxis' => [
                'title' => [
                    'text' => 'Amount',
                ],
            ],
            'tooltip' => [
                'shared' => true,
                'x' => [
                    'show' => true,
                    'format' => 'dd MMM yyyy',
                ],
                'y' => [
                    'formatter' => function ($value) {
                        return number_format($value, 2);
                    },
                ],
            ],
            'colors' => ['#22c55e', '#ef4444', '#60a5fa'], // Income: green, Expenses: red, Balance: blue
        ];
    }
}
