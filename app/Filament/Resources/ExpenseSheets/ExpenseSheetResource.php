<?php

namespace App\Filament\Resources\ExpenseSheets;

use App\Filament\Resources\ExpenseSheets\Pages\CreateExpenseSheet;
use App\Filament\Resources\ExpenseSheets\Pages\EditExpenseSheet;
use App\Filament\Resources\ExpenseSheets\Pages\ListExpenseSheets;
use App\Filament\Resources\ExpenseSheets\Tables\ExpenseSheetsTable;
use App\Models\ExpenseCategory;
use App\Models\ExpenseSheet;
use Filament\Forms;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Get;
use Filament\Forms\Components\Set;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;


class ExpenseSheetResource extends Resource
{
    protected static ?string $model = ExpenseSheet::class;

    protected static ?string $navigationGroup = 'Expense';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Expenses';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'expense_date';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Expense Information')
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Project'),

                        Forms\Components\DatePicker::make('expense_date')
                            ->required()
                            ->label('Date'),
                    ])
                    ->columns(2),

                Section::make('Expense Details')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->hiddenLabel()
                            ->relationship()
                            ->reactive()
                            ->afterStateHydrated(fn (Get $get, Set $set): mixed => $set(
                                'total_amount',
                                self::calculateTotalAmount($get('items') ?? [])
                            ))
                            ->afterStateUpdated(fn (Get $get, Set $set): mixed => $set(
                                'total_amount',
                                self::calculateTotalAmount($get('items') ?? [])
                            ))
                            ->table([
                                TableColumn::make('Category')
                                    ->width('25%')
                                    ->markAsRequired(),
                                TableColumn::make('Description')
                                    ->width('55%'),
                                TableColumn::make('Amount')
                                    ->width('20%')
                                    ->markAsRequired(),
                            ])
                            ->schema([
                                Forms\Components\Select::make('category')
                                    ->options(fn (): array => ExpenseCategory::query()
                                        ->orderBy('name')
                                        ->pluck('name', 'name')
                                        ->all())
                                    ->searchable()
                                    ->required(),

                                Forms\Components\TextInput::make('description')
                                    ->placeholder('Description'),

                                Forms\Components\TextInput::make('amount')
                                    ->numeric()
                                    ->prefix('৳')
                                    ->reactive()
                                    ->afterStateUpdated(fn (Get $get, Set $set): mixed => $set(
                                        '../../total_amount',
                                        self::calculateTotalAmount($get('../../items') ?? [])
                                    ))
                                    ->required(),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->addActionLabel('Add More Expense'),
                    ]),

                Section::make('Total')
                    ->schema([
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->prefix('৳')
                            ->numeric()
                            ->readOnly()
                            ->default(0)
                            ->dehydrated(),
                    ]),
            ])
            ->columns(1);
    }

    public static function calculateTotalAmount(array $items): float
    {
        return round(collect($items)->sum(
            fn (array $item): float => (float) ($item['amount'] ?? 0)
        ), 2);
    }

    public static function table(Table $table): Table
    {
        return ExpenseSheetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExpenseSheets::route('/'),
            'create' => CreateExpenseSheet::route('/create'),
            'edit' => EditExpenseSheet::route('/{record}/edit'),
        ];
    }
}
