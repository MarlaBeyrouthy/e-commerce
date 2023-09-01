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
            'name' => 'admin',
            'email' =>'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'address' => 'damas','bio'=>'none','city_id'=>1,'place_id'=>1,
            'phone' =>'0000',
            'contact' => 'none',
            'photo'=> 'uploads/users_photo/test',
            'photo_profile'=> 'uploads/users_photo/test',
            'permission_id'=>2,
        ],[
            'name' => 'hamza',
            'email' =>'hhh@gmail.com',
            'password' => bcrypt('12341234'),
            'address' => 'damas','bio'=>'none','city_id'=>1,'place_id'=>1,
            'phone' =>'09111111',
            'contact' => 'hello',
            'photo'=> 'uploads/users_photo/test',
            'photo_profile'=> 'uploads/users_photo/test',
            'permission_id'=>1,
        ],[
            'name' => 'marla',
            'email' =>'mmm@gmail.com',
            'password' => bcrypt('12341234'),
            'address' => 'damas','bio'=>'none','city_id'=>1,'place_id'=>1,
            'phone' =>'09111111',
            'contact' => 'hello',
            'photo'=> 'uploads/users_photo/test',
            'photo_profile'=> 'uploads/users_photo/test',
            'permission_id'=>1,
        ],[
            'name' => 'banned_user',
            'email' =>'banned@gmail.com',
            'password' => bcrypt('12341234'),
            'address' => 'damas','bio'=>'none','city_id'=>1,'place_id'=>1,
            'phone' =>'09111111',
            'contact' => 'hello',
            'photo'=> 'uploads/users_photo/test',
            'photo_profile'=> 'uploads/users_photo/test',
            'permission_id'=>4,
        ]]);
        //
    }
}
