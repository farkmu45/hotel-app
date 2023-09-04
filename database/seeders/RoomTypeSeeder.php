<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\RoomType::insert([[
            'name' => 'Single Room',
            'rates' => 100000,
            'facilities' => json_encode(['TV', '1 Bathroom', '1 Bed']),
        ], [
            'name' => 'Double Room',
            'price' => 150000,
            'facilities' => json_encode(['TV', '1 Bathroom', '2 Bed', 'Fridge']),
        ], [
            'name' => 'Studio Room',
            'price' => 300000,
            'facilities' => json_encode(['TV', '1 Bathroom', '1 Bed', 'Sofa', 'Writing Table']),
        ]]);
    }
}
