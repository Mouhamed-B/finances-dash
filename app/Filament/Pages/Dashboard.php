<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BalanceChart;
use Filament\Pages\Page;
use App\Filament\Widgets\IncomeStats;
use App\Filament\Widgets\LatestIncomes;
use App\Filament\Widgets\LatestExpenses;
use App\Filament\Widgets\IncomeExpenseChart;
class Dashboard extends Page
{
    protected static string $routePath = '/';
    protected static ?string $title = 'Dashboard';
    protected static ?int $navigationSort = -2;
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament-panels::pages.dashboard';

    protected static ?string $route;

    public function getWidgets(): array
    {
        return [
            IncomeStats::class,
            IncomeExpenseChart::class,
            LatestIncomes::class,
            LatestExpenses::class,
            BalanceChart::class,
        ];
    }

    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }

    public function getColumns(): int | string | array
    {
        return [
            'sm' => 2,
            'xl' => 6,
        ];
    }
}
