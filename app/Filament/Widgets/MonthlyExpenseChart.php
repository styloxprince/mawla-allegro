<?php

namespace App\Filament\Widgets;

use App\Models\ExpenseItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MonthlyExpenseChart extends ChartWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected ?string $heading = 'Monthly Expenses';

    protected ?string $description = 'Last 12 months expense trend, calculated from expense items.';

    protected static ?string $color = 'warning';

    protected ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $months = collect(range(11, 0))->map(
            fn (int $monthsAgo): Carbon => now()->startOfMonth()->subMonths($monthsAgo)
        );

        return [
            'datasets' => [
                [
                    'label' => 'Expenses',
                    'data' => $months
                        ->map(fn (Carbon $month): float => (float) ExpenseItem::query()
                            ->whereHas('expenseSheet', fn ($query) => $query
                                ->whereDate('expense_date', '>=', $month->toDateString())
                                ->whereDate('expense_date', '<=', $month->copy()->endOfMonth()->toDateString()))
                            ->sum('amount'))
                        ->all(),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.16)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $months
                ->map(fn (Carbon $month): string => $month->format('M Y'))
                ->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
