<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
// icons replaced with heroicon string names for Filament v3 compatibility
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerStatsOverview extends BaseWidget
{
    protected static bool $isDiscovered = false;

    protected int | string | array $columnSpan = 'full';

    protected int | array | null $columns = [
        'default' => 1,
        '@md' => 2,
        '@xl' => 4,
    ];

    public ?Customer $record = null;

    protected function getStats(): array
    {
        $totalPaid = (float) ($this->record?->bookingPayments()->sum('amount') ?? 0);
        $totalDue = (float) ($this->record?->bookings()->where('status', '!=', 'cancelled')->sum('due_amount') ?? 0);

        return [
            Stat::make('Booked Flats', number_format($this->record?->bookings()->count() ?? 0))
                ->description('Total bookings')
                ->descriptionIcon('heroicon-o-home-modern')
                ->color('info'),

            Stat::make('Total Paid', StatsOverview::formatBdt($totalPaid))
                ->description('Payment history total')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Due Amount', StatsOverview::formatBdt($totalDue))
                ->description('Outstanding booking balance')
                ->descriptionIcon('heroicon-o-credit-card')
                ->color($totalDue > 0 ? 'danger' : 'success'),

            Stat::make('Active Status', ucfirst((string) ($this->record?->bookings()->latest()->value('status') ?? 'No booking')))
                ->description('Latest booking status')
                ->descriptionIcon('heroicon-o-clipboard-document-check')
                ->color('primary'),
        ];
    }
}
