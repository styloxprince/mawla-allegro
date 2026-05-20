<?php

namespace App\Filament\Resources\Flats;

use App\Filament\Resources\Flats\Pages\CreateFlat;
use App\Filament\Resources\Flats\Pages\EditFlat;
use App\Filament\Resources\Flats\Pages\ListFlats;
use App\Filament\Resources\Flats\Tables\FlatsTable;
use App\Models\Flat;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FlatResource extends Resource
{
    protected static ?string $model = Flat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'flat_no';

    protected static string|\UnitEnum|null $navigationGroup = 'Property Management';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('flat_no')
                    ->required(),

                Forms\Components\TextInput::make('unit'),

                Forms\Components\TextInput::make('floor')
                    ->numeric(),

                Forms\Components\TextInput::make('size')
                    ->numeric()
                    ->suffix('sqft'),

                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('৳'),

                Forms\Components\Select::make('status')
                    ->options([
                        'available' => 'Available',
                        'booked' => 'Booked',
                        'sold' => 'Sold',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('available'),

                Forms\Components\TextInput::make('facing'),

                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),

            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return FlatsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    
   
    public static function getPages(): array
    {
        return [
            'index' => ListFlats::route('/'),
            'create' => CreateFlat::route('/create'),
            'edit' => EditFlat::route('/{record}/edit'),
        ];
    }
  
}