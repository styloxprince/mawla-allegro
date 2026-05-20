<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Pages\ViewCustomer;
use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Filament\Resources\Customers\Tables\CustomersTable;
use App\Models\Customer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms;
use UnitEnum;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string | UnitEnum | null $navigationGroup = 'Sales';
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

   public static function form(Schema $schema): Schema
{
    return $schema
        ->components([

            Forms\Components\Select::make('project_id')
                ->relationship('project', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\Select::make('flat_id')
                ->relationship('flat', 'flat_no')
                ->searchable()
                ->preload(),

            Forms\Components\TextInput::make('name')
                ->required(),

            Forms\Components\TextInput::make('phone'),

            Forms\Components\TextInput::make('email')
                ->email(),

            Forms\Components\TextInput::make('nid'),

            Forms\Components\Textarea::make('address'),

            Forms\Components\TextInput::make('nominee_name'),

            Forms\Components\TextInput::make('nominee_phone'),

            Forms\Components\DatePicker::make('booking_date'),

            Forms\Components\TextInput::make('total_amount')
                ->numeric()
                ->prefix('৳'),

            Forms\Components\Textarea::make('notes')
                ->columnSpanFull(),

        ])
        ->columns(2);
}
   
   
   

    public static function table(Table $table): Table
    {
        return CustomersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'view' => ViewCustomer::route('/{record}'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}
