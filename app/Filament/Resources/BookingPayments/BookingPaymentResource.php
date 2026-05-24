<?php

namespace App\Filament\Resources\BookingPayments;

use App\Filament\Resources\BookingPayments\Pages\CreateBookingPayment;
use App\Filament\Resources\BookingPayments\Pages\EditBookingPayment;
use App\Filament\Resources\BookingPayments\Pages\ListBookingPayments;
use App\Filament\Widgets\StatsOverview;
use App\Models\Booking;
use App\Models\BookingPayment;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingPaymentResource extends Resource
{
    protected static ?string $model = BookingPayment::class;

    protected static ?string $navigationGroup = 'Sales';

    protected static ?string $navigationLabel = 'Payments';

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Booking Summary')
                    ->schema([

                        Forms\Components\Placeholder::make('total_price')
                            ->label('Total Price')
                            ->content(function ($get) {

                                $booking = Booking::find($get('booking_id'));

                                return '৳ ' . number_format($booking?->final_price ?? 0);
                            }),

                        Forms\Components\Placeholder::make('paid')
                            ->label('Total Paid')
                            ->content(function ($get) {

                                $booking = Booking::find($get('booking_id'));

                                return '৳ ' . number_format($booking?->total_paid ?? 0);
                            }),

                        Forms\Components\Placeholder::make('due')
                            ->label('Current Due')
                            ->content(function ($get) {

                                $booking = Booking::find($get('booking_id'));

                                return '৳ ' . number_format($booking?->due_amount ?? 0);
                            }),

                        Forms\Components\Placeholder::make('remaining')
                            ->label('Remaining After Payment')
                            ->content(function ($get) {

                                $booking = Booking::find($get('booking_id'));

                                $due = $booking?->due_amount ?? 0;

                                $amount = (float) $get('amount');

                                return '৳ ' . number_format($due - $amount);
                            }),

                    ])
                    ->columns(4)
                    ->columnSpanFull(),

                Section::make('Payment Information')
                    ->schema([

                        Forms\Components\Select::make('booking_id')
                            ->relationship('booking', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record): string =>
                                "#{$record->id} - {$record->customer?->name} - {$record->flat?->flat_no}"
                            )
                            ->searchable(['id'])
                            ->preload()
                            ->live()
                            ->required(),

                        Forms\Components\DatePicker::make('payment_date')
                            ->default(today())
                            ->required(),

                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->prefix('৳')
                            ->live()
                            ->required(),

                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'Cash' => 'Cash',
                                'Bank' => 'Bank',
                                'Bkash' => 'Bkash',
                                'Nagad' => 'Nagad',
                            ])
                            ->live()
                            ->required(),

                        Forms\Components\Select::make('bank_id')
                            ->label('Select Bank Account')
                            ->relationship('bank', 'bank_name')
                            ->getOptionLabelFromRecordUsing(fn ($record): string =>
                                "{$record->bank_name} - {$record->account_number}"
                            )
                            ->searchable()
                            ->preload()
                            ->visible(fn ($get) =>
                                $get('payment_method') === 'Bank'
                            ),

                        Forms\Components\TextInput::make('mobile_number')
                            ->label('Mobile Number')
                            ->visible(fn ($get) =>
                                in_array($get('payment_method'), ['Bkash', 'Nagad'])
                            ),

                        Forms\Components\TextInput::make('transaction_id')
                            ->label('Transaction ID')
                            ->visible(fn ($get) =>
                                in_array($get('payment_method'), ['Bank', 'Bkash', 'Nagad'])
                            ),

                        Forms\Components\Textarea::make('note')
                            ->columnSpanFull(),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('booking.id')
                    ->label('Booking #')
                    ->sortable(),

                TextColumn::make('booking.customer.name')
                    ->label('Customer')
                    ->searchable(),

                TextColumn::make('booking.flat.flat_no')
                    ->label('Flat'),

                TextColumn::make('payment_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('amount')
                    ->formatStateUsing(fn ($state): string =>
                        StatsOverview::formatBdt($state)
                    )
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->badge()
                    ->color('info'),

                TextColumn::make('bank.bank_name')
                    ->label('Bank'),

                TextColumn::make('transaction_id')
                    ->label('Transaction ID'),

            ])
            ->filters([

                SelectFilter::make('payment_method')
                    ->options([
                        'Cash' => 'Cash',
                        'Bank' => 'Bank',
                        'Bkash' => 'Bkash',
                        'Nagad' => 'Nagad',
                    ]),

            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookingPayments::route('/'),
            'create' => CreateBookingPayment::route('/create'),
            'edit' => EditBookingPayment::route('/{record}/edit'),
        ];
    }
}
