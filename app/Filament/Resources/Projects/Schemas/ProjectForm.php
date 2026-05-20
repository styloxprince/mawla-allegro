<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('location')
                    ->default(null),
                TextInput::make('budget')
                    ->numeric()
                    ->prefix('৳')
                    ->default(null),
                Select::make('status')
                    ->options([
            'active' => 'Active',
            'completed' => 'Completed',
            'on_hold' => 'On hold',
            'cancelled' => 'Cancelled',
        ])
                    ->default('active')
                    ->required(),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
