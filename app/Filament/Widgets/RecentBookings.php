<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentBookings extends TableWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = [
        '@xl' => 2,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent Bookings')
            ->query(fn (): Builder => Booking::query()->latest()->limit(8))
            ->columns([
                TextColumn::make('customer.name')->label('Customer')->searchable(),
                TextColumn::make('flat.flat_no')->label('Flat'),
                TextColumn::make('booking_date')->date()->sortable(),
                TextColumn::make('final_price')->formatStateUsing(fn ($state): string => StatsOverview::formatBdt($state)),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'partial' => 'warning',
                        'cancelled' => 'danger',
                        default => 'info',
                    }),
            ])
            ->paginated(false);
    }
}
