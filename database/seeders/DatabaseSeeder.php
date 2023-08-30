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
        \App\Models\Customer::factory(10)->create();
        \App\Models\RoomType::factory(3)->create();
        \App\Models\Dish::factory(3)->create();

        \App\Models\LaundryType::insert([
            'name' => 'Regular',
            'price' => 10000,
        ], [
            'name' => 'Express',
            'price' => 20000,
        ]);

        \App\Models\Admin::factory()->create([
            'name' => 'Test User',
            'username' => 'admin',
        ]);
    }
}
