<?php

namespace App\Filament\Resources\Transaction;

use App\Filament\Resources\Transaction\OrderResource\Pages;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'pesanan kamar';

    protected static ?string $pluralModelLabel = 'pesanan kamar';

    protected static ?string $navigationGroup = 'Pemesanan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Section::make('Informasi Umum')
                            ->schema([
                                Placeholder::make('customer')
                                    ->label('Pelanggan')
                                    ->content(fn (?Model $record) => $record->customer->name),
                                Placeholder::make('kamar')
                                    ->content(fn (?Model $record) => $record->room->code),
                                Placeholder::make('room_type')
                                    ->label('Jenis kamar')
                                    ->content(fn (?Model $record) => $record->room->roomType->name),
                                TextInput::make('price')
                                    ->label('Harga')
                                    ->readOnly(),
                            ])->columns(2)
                            ->columnSpan(2),
                        Section::make('Tanggal Menginap')
                            ->schema([
                                Placeholder::make('check_in_date')
                                    ->label('Tanggal check-in')
                                    ->content(fn (?Model $record) => Carbon::parse($record->check_in_date)->format('Y-m-d')),
                                TimePicker::make('check_in')
                                    ->label('Waktu check-in')
                                    ->seconds(false)
                                    ->disabled(fn (?Model $record) => $record->check_in)
                                    ->required(fn (?Model $record) => ! $record->check_in)
                                    ->minDate(now()->hour(config('app.check_in_time'))->minute(0)->format('Y-m-dH:i'))
                                    ->live(),
                                Placeholder::make('check_out_date')
                                    ->label('Tanggal check-out')
                                    ->content(fn (?Model $record) => Carbon::parse($record->check_out_date)->format('Y-m-d')),
                                TimePicker::make('check_out')
                                    ->label('Waktu check-out')
                                    ->disabled(fn (?Model $record) => ! $record->check_in)
                                    ->live()
                                    ->required(fn (?Model $record) => $record->check_in)
                                    ->minDate(now()->hour(config('app.check_in_time'))->minute(0)->format('Y-m-dH:i'))
                                    ->afterStateUpdated(function (?string $state, Set $set, ?Model $record) {
                                        $formattedState = Carbon::parse($state);
                                        $checkoutTime = Carbon::parse(config('app.check_out_time'));

                                        if ($formattedState > $checkoutTime) {
                                            $currentPrice = $record->price;
                                            $roomPrice = $record->room->roomType->rates;
                                            $currentPrice += 0.5 * $roomPrice;
                                            $set('price', round($currentPrice));
                                        }

                                    })
                                    ->seconds(false),
                            ])
                            ->columnSpan(1),
                    ]),
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
                    ->label('Pelanggan'),
                TextColumn::make('room.id')
                    ->sortable()
                    ->searchable()
                    ->label('No. Kamar'),
                TextColumn::make('price')
                    ->sortable()
                    ->label('Biaya')
                    ->money('IDR'),

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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
