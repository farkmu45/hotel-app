<?php

namespace App\Filament\Resources\Transaction;

use App\Filament\Resources\Transaction\LaundryResource\Pages;
use App\Models\Laundry;
use App\Models\LaundryType;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LaundryResource extends Resource
{
    protected static ?string $model = Laundry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'pesanan laundry';

    protected static ?string $pluralModelLabel = 'pesanan laundry';

    protected static ?string $navigationGroup = 'Pemesanan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('customer_id')
                            ->label('Pelanggan')
                            ->required()
                            ->disabledOn('edit')
                            ->preload()
                            ->native(false)
                            ->searchable()
                            ->relationship(
                                name: 'customer',
                                titleAttribute: 'name',
                                modifyQueryUsing: function (Builder $query) {
                                    return $query->whereIn('id', fn ($q) => $q->select('customer_id')
                                        ->from('orders')
                                        ->where('check_out', null)
                                        ->whereNotNull('check_in'));
                                }
                            ),
                        Radio::make('laundry_type_id')
                            ->label('Jenis')
                            ->afterStateUpdated(function (string $state, Set $set, Get $get) {
                                $type = LaundryType::find($state);
                                $weight = $get('weight');
                                if ($weight) {
                                    $price = $type->price * $weight;
                                    $set('price', $price);
                                }
                            })
                            ->live()
                            ->required()
                            ->options(LaundryType::all()->pluck('name', 'id')),
                        TextInput::make('weight')
                            ->suffix('kg')
                            ->live()
                            ->numeric()
                            ->afterStateUpdated(function (string $state, Set $set, Get $get) {
                                $type = LaundryType::find($get('laundry_type_id'));
                                if ($type) {
                                    $price = $type->price * $state;
                                    $set('price', $price);
                                }
                            })
                            ->maxLength(10)
                            ->gte(0.1, true)
                            ->required()
                            ->label('Berat'),
                        TextInput::make('price')
                            ->readOnly()
                            ->required()
                            ->label('Harga'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->label('Kode'),
                TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable()
                    ->label('Pelanggan'),
                TextColumn::make('laundryType.name')
                    ->sortable()
                    ->label('Jenis'),
                TextColumn::make('weight')
                    ->sortable()
                    ->suffix(' kg')
                    ->label('Berat'),
                TextColumn::make('price')
                    ->sortable()
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->label('Total harga'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListLaundries::route('/'),
            'create' => Pages\CreateLaundry::route('/create'),
            'edit' => Pages\EditLaundry::route('/{record}/edit'),
        ];
    }
}
