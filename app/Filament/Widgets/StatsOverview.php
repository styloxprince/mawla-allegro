<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Customer;
use App\Models\ExpenseItem;
use App\Models\Flat;
use App\Models\Project;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;

    protected ?string $heading = 'Real Estate ERP Dashboard';

    protected ?string $description = 'Live portfolio, sales, dues, receipts, and expense metrics.';

    protected int | array | null $columns = [
        'default' => 1,
        '@md' => 2,
        '@xl' => 4,
    ];

    protected function getStats(): array
    {
        $totalExpense = (float) ExpenseItem::query()->sum('amount');
        $monthlyExpense = (float) ExpenseItem::query()
            ->whereHas('expenseSheet', fn ($query) => $query
                ->whereYear('expense_date', now()->year)
                ->whereMonth('expense_date', now()->month))
            ->sum('amount');
        $totalSales = (float) Booking::query()
            ->where('status', '!=', 'cancelled')
            ->sum('final_price');
        $totalDue = (float) Booking::query()
            ->where('status', '!=', 'cancelled')
            ->sum('due_amount');
        $totalReceived = (float) BookingPayment::query()->sum('amount');

        return [
            Stat::make('Total Projects', number_format(Project::query()->count()))
                ->description('All project records')
                ->descriptionIcon(Heroicon::BuildingOffice2)
                ->color('primary'),

            Stat::make('Total Flats', number_format(Flat::query()->count()))
                ->description('Complete flat inventory')
                ->descriptionIcon(Heroicon::HomeModern)
                ->color('info'),

            Stat::make('Sold Flats', number_format(Flat::query()->where('status', 'sold')->count()))
                ->description('Completed flat sales')
                ->descriptionIcon(Heroicon::ReceiptPercent)
                ->color('success'),

            Stat::make('Available Flats', number_format(Flat::query()->where('status', 'available')->count()))
                ->description('Ready for booking')
                ->descriptionIcon(Heroicon::Wallet)
                ->color('warning'),

            Stat::make('Total Customers', number_format(Customer::query()->count()))
                ->description('Registered customers')
                ->descriptionIcon(Heroicon::UserGroup)
                ->color('gray'),

            Stat::make('Total Expense', self::formatBdt($totalExpense))
                ->description('Calculated from expense items')
                ->descriptionIcon(Heroicon::Banknotes)
                ->color('danger'),

            Stat::make('Monthly Expense', self::formatBdt($monthlyExpense))
                ->description(now()->format('F Y'))
                ->descriptionIcon(Heroicon::ChartBar)
                ->color('warning'),

            Stat::make('Total Sales', self::formatBdt($totalSales))
                ->description('Non-cancelled booking value')
                ->descriptionIcon(Heroicon::CurrencyBangladeshi)
                ->color('success'),

            Stat::make('Total Due', self::formatBdt($totalDue))
                ->description('Outstanding booking balance')
                ->descriptionIcon(Heroicon::CreditCard)
                ->color('danger'),

            Stat::make('Total Received', self::formatBdt($totalReceived))
                ->description('All booking payments')
                ->descriptionIcon(Heroicon::Banknotes)
                ->color('primary'),
        ];
    }

    public static function formatBdt(float | int | string | null $amount): string
    {
        return '৳ ' . number_format((float) ($amount ?? 0));
    }
}
