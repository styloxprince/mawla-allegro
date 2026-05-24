<?php

namespace App\Filament\Resources\Bookings;

use App\Filament\Resources\Bookings\Pages\CreateBooking;
use App\Filament\Resources\Bookings\Pages\EditBooking;
use App\Filament\Resources\Bookings\Pages\ListBookings;
use App\Models\Booking;
use App\Models\Flat;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\Get;
use Filament\Forms\Components\Set;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationGroup = 'Sales';

    protected static ?string $navigationLabel = 'Bookings';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'id';

    public static function getRecordTitleAttribute(): ?string
    {
        return 'booking_code';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                /*
                |--------------------------------------------------------------------------
                | Booking Information
                |--------------------------------------------------------------------------
                */

                Section::make('Booking Information')
                    ->schema([

                        Forms\Components\Select::make('project_id')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('flat_id')
                            ->relationship('flat', 'flat_no')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (?string $state, Set $set): void {

                                $flat = Flat::find($state);

                                if (!$flat) {
                                    return;
                                }

                                $size = (float) ($flat->size ?? 0);

                                $set('flat_size', $size);

                                $rate = 0;

                                $total = $size * $rate;

                                $set('total_price', $total);

                                $set('final_price', $total);
                            }),

                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\DatePicker::make('booking_date')
                            ->default(today())
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'Pending' => 'Pending',
                                'Partial' => 'Partial',
                                'Paid' => 'Paid',
                            ])
                            ->default('Pending')
                            ->disabled()
                            ->dehydrated(),

                    ])
                    ->columns(2),

                /*
                |--------------------------------------------------------------------------
                | Pricing Information
                |--------------------------------------------------------------------------
                */

                Section::make('Pricing Information')
                    ->schema([

                        Forms\Components\TextInput::make('flat_size')
                            ->numeric()
                            ->suffix('SFT')
                            ->readOnly()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('rate_per_sft')
                            ->numeric()
                            ->prefix('৳')
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, $state): void {

                                $size = (float) ($get('flat_size') ?? 0);

                                $discount = (float) ($get('discount') ?? 0);

                                $total = $size * (float) $state;

                                $final = $total - $discount;

                                $set('total_price', $total);

                                $set('final_price', $final);
                            }),

                        Forms\Components\TextInput::make('total_price')
                            ->numeric()
                            ->prefix('৳')
                            ->readOnly()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('discount')
                            ->numeric()
                            ->prefix('৳')
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, $state): void {

                                $total = (float) ($get('total_price') ?? 0);

                                $final = $total - (float) $state;

                                $set('final_price', $final);
                            }),

                        Forms\Components\TextInput::make('final_price')
                            ->numeric()
                            ->prefix('৳')
                            ->readOnly()
                            ->dehydrated(),

                    ])
                    ->columns(2),

                /*
                |--------------------------------------------------------------------------
                | Payment Status
                |--------------------------------------------------------------------------
                */

                Section::make('Payment Status')
                    ->schema([

                        Forms\Components\TextInput::make('total_paid')
                            ->label('Total Payment')
                            ->prefix('৳')
                            ->readOnly()
                            ->formatStateUsing(fn ($record) => $record?->total_paid ?? 0),

                        Forms\Components\TextInput::make('due_amount')
                            ->label('Due Amount')
                            ->prefix('৳')
                            ->readOnly()
                            ->formatStateUsing(fn ($record) => $record?->due_amount ?? 0),

                        Forms\Components\TextInput::make('payment_percentage')
                            ->label('Payment Progress')
                            ->suffix('%')
                            ->readOnly()
                            ->formatStateUsing(fn ($record) => $record?->payment_percentage ?? 0),

                        Forms\Components\Textarea::make('note')
                            ->columnSpanFull(),

                    ])
                    ->columns(3),

            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('flat.flat_no')
                    ->label('Flat')
                    ->searchable(),

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(),

                TextColumn::make('booking_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('final_price')
                    ->label('Flat Price')
                    ->money('BDT')
                    ->sortable(),

                TextColumn::make('total_paid')
                    ->label('Paid')
                    ->money('BDT')
                    ->sortable(),

                TextColumn::make('due_amount')
                    ->label('Due')
                    ->money('BDT')
                    ->color('danger')
                    ->sortable(),

                TextColumn::make('payment_percentage')
                    ->label('Paid %')
                    ->suffix('%')
                    ->badge()
                    ->color(fn ($state): string => ((float) $state >= 100)
                        ? 'success'
                        : 'warning'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Paid' => 'success',
                        'Partial' => 'warning',
                        default => 'danger',
                    }),

            ])

            ->filters([

                SelectFilter::make('project_id')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Partial' => 'Partial',
                        'Paid' => 'Paid',
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
            'index' => ListBookings::route('/'),
            'create' => CreateBooking::route('/create'),
            'edit' => EditBooking::route('/{record}/edit'),
        ];
    }
}