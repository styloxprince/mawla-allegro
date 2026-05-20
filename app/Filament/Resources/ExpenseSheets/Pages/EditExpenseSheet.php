<?php

namespace App\Filament\Resources\ExpenseSheets\Pages;

use App\Filament\Resources\ExpenseSheets\ExpenseSheetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditExpenseSheet extends EditRecord
{
    protected static string $resource = ExpenseSheetResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $items = $this->form->getRawState()['items'] ?? [];

        $data['total_amount'] = ExpenseSheetResource::calculateTotalAmount($items);
        unset($data['items']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
