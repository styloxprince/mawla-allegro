<?php

namespace App\Filament\Resources\ExpenseSheets\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;

class ExpenseSheetForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('project_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('expense_date')
                    ->required(),
                Textarea::make('note')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
