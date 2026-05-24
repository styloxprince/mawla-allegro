<?php

namespace App\Filament\Resources\Banks;

use App\Filament\Resources\Banks\Pages\CreateBank;
use App\Filament\Resources\Banks\Pages\EditBank;
use App\Filament\Resources\Banks\Pages\ListBanks;
use App\Models\Bank;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class BankResource extends Resource
{
    protected static ?string $model = Bank::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'bank_name';

    protected static ?string $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Banks';

    protected static ?string $modelLabel = 'Bank';

    protected static ?string $pluralModelLabel = 'Banks';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Bank Information')
                    ->schema([

                        Forms\Components\TextInput::make('bank_name')
                            ->label('Bank Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('account_name')
                            ->label('Account Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('account_number')
                            ->label('Account Number')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('branch')
                            ->label('Branch')
                            ->maxLength(255),

                        Forms\Components\Toggle::make('status')
                            ->label('Active')
                            ->default(true),

                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('bank_name')
                ->searchable(),

            Tables\Columns\TextColumn::make('account_name')
                ->searchable(),

            Tables\Columns\TextColumn::make('account_number')
                ->searchable(),

            Tables\Columns\TextColumn::make('branch'),

            Tables\Columns\IconColumn::make('status')
                ->boolean(),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ])
        ->filters([
            //
        ])
        ->recordActions([
            \Filament\Actions\EditAction::make(),
            \Filament\Actions\DeleteAction::make(),
        ])
        ->toolbarActions([
            //
        ]);
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
            'index' => ListBanks::route('/'),
            'create' => CreateBank::route('/create'),
            'edit' => EditBank::route('/{record}/edit'),
        ];
    }
}