<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Project;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ProjectBookingsTable extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected int | string | array $columnSpan = 'full';

    public ?Project $record = null;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Project Booking Summary')
            ->query(fn (): Builder => Booking::query()
                ->where('project_id', $this->record?->id)
                ->latest())
            ->columns([
                TextColumn::make('customer.name')->label('Customer')->searchable(),
                TextColumn::make('flat.flat_no')->label('Flat'),
                TextColumn::make('final_price')->formatStateUsing(fn ($state): string => StatsOverview::formatBdt($state)),
                TextColumn::make('total_paid')->formatStateUsing(fn ($state): string => StatsOverview::formatBdt($state)),
                TextColumn::make('due_amount')->formatStateUsing(fn ($state): string => StatsOverview::formatBdt($state)),
                TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'completed' => 'success',
                    'partial' => 'warning',
                    'cancelled' => 'danger',
                    default => 'info',
                }),
            ]);
    }
}
