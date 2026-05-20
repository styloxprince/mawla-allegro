<?php

namespace App\Filament\Resources\Flats\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class FlatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('project_id')
                    ->required()
                    ->numeric(),
                TextInput::make('flat_no')
                    ->required(),
                TextInput::make('unit')
                    ->default(null),
                TextInput::make('floor')
                    ->numeric()
                    ->default(null),
                TextInput::make('size')
                    ->numeric()
                    ->default(null),
                TextInput::make('price')
                    ->numeric()
                    ->default(null)
                    ->prefix('$'),
                Select::make('status')
                    ->options(['available' => 'Available', 'booked' => 'Booked', 'sold' => 'Sold', 'cancelled' => 'Cancelled'])
                    ->default('available')
                    ->required(),
                TextInput::make('facing')
                    ->default(null),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
