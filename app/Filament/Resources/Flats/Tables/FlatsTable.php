<?php

namespace App\Filament\Resources\Flats\Tables;

use App\Filament\Widgets\StatsOverview;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FlatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('flat_no')
                    ->label('Flat No')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('floor')
                    ->label('Floor')
                    ->sortable(),

                TextColumn::make('size')
                    ->label('Size')
                    ->suffix(' sqft'),

                TextColumn::make('price')
                    ->label('Price')
                    ->formatStateUsing(fn ($state): string => StatsOverview::formatBdt($state)),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'booked' => 'warning',
                        'sold' => 'danger',
                        default => 'gray',
                    }),

            ])

            ->filters([
                //
            ])

            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
