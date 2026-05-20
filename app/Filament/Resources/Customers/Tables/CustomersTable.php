<?php

namespace App\Filament\Resources\Customers\Tables;

use App\Filament\Widgets\StatsOverview;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('Customer Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),

                TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable(),

                TextColumn::make('flat.flat_no')
                    ->label('Flat'),

                TextColumn::make('total_amount')
                    ->label('Booking Amount')
                    ->formatStateUsing(fn ($state): string => StatsOverview::formatBdt($state)),

                TextColumn::make('booking_date')
                    ->date(),

            ])

            ->filters([
                //
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
