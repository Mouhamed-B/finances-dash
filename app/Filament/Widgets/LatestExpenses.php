<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class LatestExpenses extends TableWidget
{
    protected int | string | array $columnSpan = [
        'sm' => 2,
        'md' => 3,
    ];

    protected function getTableQuery(): Builder
    {
        return Expense::query()->where('user_id', Auth::id())->latest()->limit(7);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('note')->searchable(),
            TextColumn::make('amount')->money('USD')->sortable(),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ];
    }
}
