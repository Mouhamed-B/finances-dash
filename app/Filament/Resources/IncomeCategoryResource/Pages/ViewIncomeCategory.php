<?php

namespace App\Filament\Resources\IncomeCategoryResource\Pages;

use App\Filament\Resources\IncomeCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewIncomeCategory extends ViewRecord
{
    protected static string $resource = IncomeCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
