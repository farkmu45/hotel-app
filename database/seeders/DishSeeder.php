<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Dish::insert([[
            'name' => 'Nasi Lemak',
            'price' => 10000,
        ], [
            'name' => 'Nasi Goreng',
            'price' => 9000,
        ], [
            'name' => 'Teh Tarik',
            'price' => 6000,
        ], [
            'name' => 'Lalapan Ayam Goreng',
            'price' => 10000,
        ], [
            'name' => 'Lalapan Lele Goreng',
            'price' => 7000,
        ]]);
    }
}
