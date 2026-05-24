<?php

namespace App\Filament\Widgets;

use App\Models\Project;
// icons replaced with heroicon string names for Filament v3 compatibility
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProjectStatsOverview extends BaseWidget
{
    protected static bool $isDiscovered = false;

    protected int | string | array $columnSpan = 'full';

    protected int | array | null $columns = [
        'default' => 1,
        '@md' => 2,
        '@xl' => 4,
    ];

    public ?Project $record = null;

    protected function getStats(): array
    {
        $budget = (float) ($this->record?->budget ?? 0);
        $expense = (float) ($this->record?->expenseItems()->sum('amount') ?? 0);
        $remaining = $budget - $expense;
        $progress = $budget > 0 ? min(100, round(($expense / $budget) * 100, 2)) : 0;

        return [
            Stat::make('Total Budget', StatsOverview::formatBdt($budget))
                ->description('Approved project budget')
                ->descriptionIcon('heroicon-o-wallet')
                ->color('primary'),

            Stat::make('Total Expense', StatsOverview::formatBdt($expense))
                ->description('Calculated from expense items')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('danger'),

            Stat::make('Remaining Budget', StatsOverview::formatBdt($remaining))
                ->description($remaining >= 0 ? 'Budget still available' : 'Over budget')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($remaining >= 0 ? 'success' : 'danger'),

            Stat::make('Expense Progress', "{$progress}%")
                ->description('Budget utilization')
                ->descriptionIcon('heroicon-o-receipt-percent')
                ->chart([$progress, max(0, 100 - $progress)])
                ->color(match (true) {
                    $progress >= 90 => 'danger',
                    $progress >= 70 => 'warning',
                    default => 'success',
                }),

            Stat::make('Sold Flats', number_format($this->record?->flats()->where('status', 'sold')->count() ?? 0))
                ->description('Completed sales')
                ->descriptionIcon('heroicon-o-home-modern')
                ->color('success'),

            Stat::make('Available Flats', number_format($this->record?->flats()->where('status', 'available')->count() ?? 0))
                ->description('Available inventory')
                ->descriptionIcon('heroicon-o-building-office-2')
                ->color('warning'),

            Stat::make('Bookings', number_format($this->record?->bookings()->count() ?? 0))
                ->description('Project bookings')
                ->descriptionIcon('heroicon-o-clipboard-document-check')
                ->color('info'),

            Stat::make('Booking Sales', StatsOverview::formatBdt((float) ($this->record?->bookings()->where('status', '!=', 'cancelled')->sum('final_price') ?? 0)))
                ->description('Non-cancelled booking value')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('primary'),
        ];
    }
}
