<?php

namespace App\Filament\Resources\Master;

use App\Filament\Resources\Master\LaundryTypeResource\Pages;
use App\Models\LaundryType;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaundryTypeResource extends Resource
{
    protected static ?string $model = LaundryType::class;

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $modelLabel = 'data jenis laundry';

    protected static ?string $pluralModelLabel = 'jenis laundry';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(100)
                            ->label('Nama')
                            ->required(),
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->mask(RawJs::make(<<<'JS'
                                $money($input)
                            JS))
                            ->maxLength(10)
                            ->label('Harga'),

                    ])
                    ->columns(2),
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
                TextColumn::make('price')
                    ->label('Harga')
                    ->sortable()
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLaundryTypes::route('/'),
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
