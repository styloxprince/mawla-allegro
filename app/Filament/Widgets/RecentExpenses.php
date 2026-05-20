<?php

namespace App\Filament\Widgets;

use App\Models\ExpenseSheet;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentExpenses extends TableWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = [
        '@xl' => 2,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent Expenses')
            ->query(fn (): Builder => ExpenseSheet::query()
                ->withSum('items as items_total_amount', 'amount')
                ->latest()
                ->limit(8))
            ->columns([
                TextColumn::make('project.name')->label('Project')->searchable(),
                TextColumn::make('expense_date')->label('Date')->date()->sortable(),
                TextColumn::make('items_total_amount')
                    ->label('Amount')
                    ->formatStateUsing(fn ($state): string => StatsOverview::formatBdt($state)),
            ])
            ->paginated(false);
    }
}
