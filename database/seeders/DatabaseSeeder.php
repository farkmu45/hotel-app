<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Customer::factory(20)->create();

        $this->call(RoomTypeSeeder::class);

        $this->call(LaundryTypeSeeder::class);

        $this->call(DishSeeder::class);

        $this->call(RoomSeeder::class);

        \App\Models\Admin::factory()->create([
            'name' => 'Test User',
            'username' => 'admin',
        ]);
    }
}
