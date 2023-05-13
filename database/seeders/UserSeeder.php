<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([[
            'name' => 'hamza',
            'email' =>'hhh@gmail.com',
            'password' => bcrypt('12341234'),
            'address' => 'damas',
            'phone' =>'09111111',
            //'verified'=>'1',
            'contact' => 'hello',
        ],[
            'name' => 'marla',
            'email' =>'mmm@gmail.com',
            'password' => bcrypt('12341234'),
            'address' => 'damas',
            'phone' =>'09111111',
            //'verified'=>'1',
            'contact' => 'hello',
        ]]);
        //
    }
}
