<?php

namespace App\Filament\Resources\IncomeResource\Pages;

use App\Filament\Resources\IncomeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateIncome extends CreateRecord
{
    protected static string $resource = IncomeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        $data['note'] = $data['note'] ?? "";
        return $data;
    }
}
