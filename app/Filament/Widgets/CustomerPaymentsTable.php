<?php

namespace App\Filament\Widgets;

use App\Models\BookingPayment;
use App\Models\Customer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class CustomerPaymentsTable extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected int | string | array $columnSpan = 'full';

    public ?Customer $record = null;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Payment History')
            ->query(fn (): Builder => BookingPayment::query()
                ->whereHas('booking', fn (Builder $query): Builder => $query->where('customer_id', $this->record?->id))
                ->latest())
            ->columns([
                TextColumn::make('booking.id')->label('Booking #'),
                TextColumn::make('booking.flat.flat_no')->label('Flat'),
                TextColumn::make('payment_date')->date()->sortable(),
                TextColumn::make('amount')->formatStateUsing(fn ($state): string => StatsOverview::formatBdt($state)),
                TextColumn::make('payment_method')->badge()->color('info'),
            ]);
    }
}
