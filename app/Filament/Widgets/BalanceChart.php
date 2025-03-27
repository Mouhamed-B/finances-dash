<?php

namespace App\Filament\Widgets;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use App\Models\Income;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class BalanceChart extends ApexChartWidget
{
    protected static ?string $heading = 'Balance over time';
    protected static ?string $chartId = 'balanceChart';

    protected int | string | array $columnSpan = [
        'sm' => 2,
        'md' => 6,
    ];

    public function getType(): string {
        return 'line';
    }

    protected function getOptions(): array
    {
        $userId = Auth::id();

        $incomeData = Income::where('user_id', $userId)
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->map(function ($group) {
                return $group->sum('amount');
            })
            ->sortKeys();

        $expenseData = Expense::where('user_id', $userId)
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->map(function ($group) {
                return -$group->sum('amount');
            })
            ->sortKeys();

        $dates = array_unique(array_merge($incomeData->keys()->toArray(), $expenseData->keys()->toArray()));
        sort($dates);

        // Calculate running balance
        $runningBalance = 0;
        $balanceData = collect($dates)->map(function ($date) use ($incomeData, $expenseData, &$runningBalance) {
            $runningBalance += ($incomeData[$date] ?? 0) + ($expenseData[$date] ?? 0);
            return $runningBalance;
        });

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
                'toolbar' => [
                    'show' => true,
                ],
                'stacked' => false,
            ],
            'series' => [
                [
                    'name' => 'Balance',
                    'data' => $balanceData->values()->toArray(),
                ],
            ],
            'xaxis' => [
                'categories' => $dates,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#60a5fa'],
            'stroke' => [
                'curve' => 'smooth',
                'width' => 2,
            ],
            'grid' => [
                'borderColor' => '#e2e8f0',
            ],
            'tooltip' => [
                'theme' => 'light',
                'y' => [
                    'formatter' => 'function (val) { return "$" + val.toFixed(2) }',
                ],
            ],
        ];
    }

    protected function updateChartData(): array
    {
        return $this->getOptions();
    }
} 