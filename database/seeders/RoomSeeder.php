<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Room::create([
            'room_type_id' => 1,
        ]);
        Room::create([
            'room_type_id' => 2,
        ]);
        Room::create([
            'room_type_id' => 3,
        ]);
        Room::create([
            'room_type_id' => 2,
        ]);
        Room::create([
            'room_type_id' => 1,
        ]);
        Room::create([
            'room_type_id' => 2,
        ]);

        Room::create([
            'room_type_id' => 3,
        ]);
    }
}
