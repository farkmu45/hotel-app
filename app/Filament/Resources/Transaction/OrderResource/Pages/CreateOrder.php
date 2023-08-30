<?php

namespace App\Filament\Resources\Transaction\OrderResource\Pages;

use App\Filament\Resources\Transaction\OrderResource;
use App\Models\Room;
use App\Models\RoomType;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\Concerns\HasWizard;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Builder;

class CreateOrder extends CreateRecord
{
    use HasWizard;

    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['room_type_id']);

        return $data;
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Informasi umum')
                ->schema([
                    Select::make('customer_id')
                        ->label('Pelanggan')
                        ->required()
                        ->preload()
                        ->native(false)
                        ->searchable()
                        ->relationship(
                            name: 'customer',
                            titleAttribute: 'name',
                            modifyQueryUsing: function (Builder $query) {
                                return $query->whereNotIn(
                                    'id',
                                    fn ($q) => $q->select('customer_id')
                                        ->from('orders')
                                        ->where('check_out', null)
                                        ->orWhere('check_in', null)
                                );
                            }
                        )->columnSpanFull(),
                    DatePicker::make('check_in_date')
                        ->label('Tanggal check-in')
                        ->live()
                        ->minDate(now()->startOfHour())
                        ->afterStateUpdated(function (Set $set) {
                            $set('check_out_date', null);
                            $set('room_type_id', null);
                            $set('room_id', null);
                        })
                        ->default(now())
                        ->native(false),
                    DatePicker::make('check_out_date')
                        ->label('Tanggal check-out')
                        ->required()
                        ->afterStateUpdated(function (Set $set) {
                            $set('price', null);
                            $set('room_type_id', null);
                            $set('room_id', null);
                        })
                        ->minDate(fn (Get $get) => Carbon::parse($get('check_in_date'))->startOfDay()->addDay())
                        ->native(false),
                ])->columns(),
            Step::make('Pilihan kamar')
                ->schema([
                    Select::make('room_type_id')
                        ->label('Jenis Kamar')
                        ->required()
                        ->native(false)
                        ->live()
                        ->options(RoomType::all()->pluck('name', 'id'))
                        ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                            $checkInDate = Carbon::parse($get('check_in_date'))->format('Y-m-d');

                            $room = Room::where('room_type_id', $state)
                                ->whereNotIn(
                                    'id',
                                    fn ($q) => $q->select('room_id')->from('orders')
                                        ->where('check_out_date', '>', $checkInDate)
                                )
                                ->first();

                            $set('room_id', $room->id ?? null);

                            $stayLength = Carbon::parse($get('check_in_date'))
                                ->startOfDay()
                                ->diffInDays(
                                    Carbon::parse($get('check_out_date'))->startOfDay()
                                );

                            if ($room->roomType->rates ?? null) {
                                $set('price', $room->roomType->rates * $stayLength);
                            } else {
                                $set('price', null);
                            }
                        }),
                    TextInput::make('room_id')
                        ->readOnly()
                        ->required()
                        ->label('Kamar')
                        ->required(),
                    TextInput::make('price')
                        ->readOnly()
                        ->label('Harga')
                        ->required(),
                ])->columns(3),
        ];
    }
}
