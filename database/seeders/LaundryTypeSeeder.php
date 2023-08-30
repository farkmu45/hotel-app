<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LaundryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\LaundryType::insert([[
            'name' => 'Regular',
            'price' => 10000,
        ], [
            'name' => 'Express',
            'price' => 20000,
        ]]);
    }
}
