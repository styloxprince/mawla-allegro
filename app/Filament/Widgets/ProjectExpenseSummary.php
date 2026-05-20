<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ProjectExpenseSummary extends TableWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Project-wise Expense Summary')
            ->query(fn (): Builder => Project::query()
                ->withSum('expenseItems as total_expense', 'amount')
                ->withCount([
                    'flats as total_flats',
                    'flats as sold_flats' => fn (Builder $query): Builder => $query->where('status', 'sold'),
                    'flats as available_flats' => fn (Builder $query): Builder => $query->where('status', 'available'),
                ]))
            ->columns([
                TextColumn::make('name')
                    ->label('Project Name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('total_expense')
                    ->label('Total Expense')
                    ->formatStateUsing(fn ($state): string => StatsOverview::formatBdt($state))
                    ->sortable(),

                TextColumn::make('total_flats')
                    ->label('Total Flats')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('sold_flats')
                    ->label('Sold Flats')
                    ->numeric()
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('available_flats')
                    ->label('Available Flats')
                    ->numeric()
                    ->badge()
                    ->color('warning')
                    ->sortable(),
            ])
            ->defaultSort('name');
    }
}
