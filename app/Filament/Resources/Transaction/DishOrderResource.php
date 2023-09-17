<?php

namespace App\Filament\Resources\Transaction;

use App\Filament\Resources\Transaction\DishOrderResource\Pages;
use App\Models\Dish;
use App\Models\DishOrder;
use Filament\Forms\Components\Repeater;
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

class DishOrderResource extends Resource
{
    protected static ?string $model = DishOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'pesanan makanan & minuman';

    protected static ?string $pluralModelLabel = 'pesanan makanan & minuman';

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
                        Repeater::make('items')
                            ->relationship()
                            ->minItems(1)
                            ->label('Detail pesanan')
                            ->addActionLabel('Tambah item')
                            ->schema([
                                Select::make('dish_id')
                                    ->relationship(
                                        name: 'dish',
                                        titleAttribute: 'name'
                                    )
                                    ->label('Makanan / Minuman')
                                    ->afterStateUpdated(function (string $state, Set $set, Get $get) {
                                        $dish = Dish::find($state);
                                        $price = $dish->price * $get('qty');
                                        $set('price_per_item', $dish->price);
                                        $set('price', $price);
                                    })
                                    ->live()
                                    ->required(),
                                TextInput::make('qty')
                                    ->default(1)
                                    ->gte(1, true)
                                    ->maxLength(5)
                                    ->live()
                                    ->numeric()
                                    ->afterStateUpdated(function (string $state, Set $set, Get $get) {
                                        $dish = Dish::find($get('dish_id'));
                                        if ($dish) {
                                            $price = $dish->price * $state;
                                            $set('price_per_item', $dish->price);
                                            $set('price', $price);
                                        }
                                    })
                                    ->required(),
                                TextInput::make('price_per_item')
                                    ->readOnly()
                                    ->label('Harga'),
                                TextInput::make('price')
                                    ->readOnly()
                                    ->label('Total'),
                            ])
                            ->columns(4),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable()
                    ->label('Pelanggan'),
                TextColumn::make('room.id')
                    ->searchable()
                    ->label('Kode Kamar'),
                TextColumn::make('items_sum_price')
                    ->sortable()
                    ->label('Total')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->sum('items', 'price'),
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
            'index' => Pages\ListDishOrders::route('/'),
            'create' => Pages\CreateDishOrder::route('/create'),
            'edit' => Pages\EditDishOrder::route('/{record}/edit'),
        ];
    }
}
