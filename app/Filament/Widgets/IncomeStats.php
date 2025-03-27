<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Income;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class IncomeStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $userId = Auth::id();
        $thirtyDaysAgo = now()->subDays(30);

        $totalIncome = Income::where('user_id', $userId)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('amount');

        $totalExpenses = Expense::where('user_id', $userId)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('amount');

        $balance = $totalIncome - $totalExpenses;

        return [
            Stat::make('Income (30 days)', number_format($totalIncome, 2))
                ->description('Total income in the last 30 days')
                ->color('success'),

            Stat::make('Expenses (30 days)', number_format($totalExpenses, 2))
                ->description('Total expenses in the last 30 days')
                ->color('danger'),

            Stat::make('Balance', number_format($balance, 2))
                ->description('Income minus expenses')
                ->color($balance >= 0 ? 'success' : 'danger'),
        ];
    }
}
