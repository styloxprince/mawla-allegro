<?php

namespace App\Filament\Resources\ExpenseSheets\Pages;

use App\Filament\Resources\ExpenseSheets\ExpenseSheetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExpenseSheet extends CreateRecord
{
    protected static string $resource = ExpenseSheetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $items = $this->form->getRawState()['items'] ?? [];

        $data['total_amount'] = ExpenseSheetResource::calculateTotalAmount($items);
        unset($data['items']);

        return $data;
    }
}
