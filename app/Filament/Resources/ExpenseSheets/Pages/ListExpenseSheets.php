<?php

namespace App\Filament\Resources\ExpenseSheets\Pages;

use App\Filament\Resources\ExpenseSheets\ExpenseSheetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExpenseSheets extends ListRecords
{
    protected static string $resource = ExpenseSheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
