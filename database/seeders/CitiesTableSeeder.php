<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cities')->insert([
            ['city' => 'Damascus'],
            ['city' => 'Aleppo'],
            ['city' => 'Latakia'],
            ['city' => 'Hama'],
        ]);
    }
}
