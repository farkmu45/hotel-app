<?php

namespace App\Filament\Resources\Master;

use App\Filament\Resources\Master\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'data pelanggan';

    protected static ?string $pluralModelLabel = 'pelanggan';

    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->maxLength(200)
                            ->label('Nama')
                            ->required(),
                        TextInput::make('email')
                            ->maxLength(200)
                            ->required()
                            ->email(),
                        TextInput::make('city')
                            ->label('Kota')
                            ->maxLength(100)
                            ->required(),
                        TextInput::make('phone_number')
                            ->label('No. Telepon')
                            ->tel()
                            ->required(),
                        Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'MALE' => 'Laki-laki',
                                'FEMALE' => 'Perempuan',
                            ])
                            ->columnSpanFull()
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('city')
                    ->label('Kota')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->searchable()
                    ->label('No. Telepon'),
                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn (string $state): string => $state === 'MALE' ? 'Laki-laki' : 'Perempuan'),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
