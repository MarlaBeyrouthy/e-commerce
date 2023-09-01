<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class User2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([[
            'Id'=> 100,
            'name' => 'noore',
            'email' =>'nnn1@gmail.com',
            'password' => bcrypt('12341234'),
            'address' => 'damas','bio'=>'none','city_id'=>1,'place_id'=>1,
            'phone' =>'0000',
            'contact' => 'none',
            'photo'=> 'uploads/users_photo/test',
            'photo_profile'=> 'uploads/users_photo/test',
            'permission_id'=>1,
        ],[
            'Id'=> 101,
            'name' => 'hamza',
            'email' =>'hhh1@gmail.com',
            'password' => bcrypt('12341234'),
            'address' => 'damas','bio'=>'none','city_id'=>1,'place_id'=>1,
            'phone' =>'09111111',
            'contact' => 'hello',
            'photo'=> 'uploads/users_photo/test',
            'photo_profile'=> 'uploads/users_photo/test',
            'permission_id'=>1,
        ],[
            'Id'=> 102,
            'name' => 'marla',
            'email' =>'mmm1@gmail.com',
            'password' => bcrypt('12341234'),
            'address' => 'damas','bio'=>'none','city_id'=>1,'place_id'=>1,
            'phone' =>'09111111',
            'contact' => 'hello',
            'photo'=> 'uploads/users_photo/test',
            'photo_profile'=> 'uploads/users_photo/test',
            'permission_id'=>1,
        ],[
            'Id'=> 103,
            'name' => 'roaa',
            'email' =>'rrr1@gmail.com',
            'password' => bcrypt('12341234'),
            'address' => 'damas','bio'=>'none','city_id'=>1,'place_id'=>1,
            'phone' =>'09111111',
            'contact' => 'hello',
            'photo'=> 'uploads/users_photo/test',
            'photo_profile'=> 'uploads/users_photo/test',
            'permission_id'=>1,
        ],[
            'Id'=> 104,
            'name' => 'lory',
            'email' =>'lll1@gmail.com',
            'password' => bcrypt('12341234'),
            'address' => 'damas','bio'=>'none','city_id'=>1,'place_id'=>1,
            'phone' =>'09111111',
            'contact' => 'hello',
            'photo'=> 'uploads/users_photo/test',
            'photo_profile'=> 'uploads/users_photo/test',
            'permission_id'=>1,
        ],[
            'Id'=> 110,
            'name' => 'worker1',
            'email' =>'worker1@gmail.com',
            'password' => bcrypt('12341234'),
            'address' => 'damas','bio'=>'none','city_id'=>1,'place_id'=>1,
            'phone' =>'09111111',
            'contact' => 'hello',
            'photo'=> 'uploads/users_photo/test',
            'photo_profile'=> 'uploads/users_photo/test',
            'permission_id'=>3,
        ],[
            'Id'=> 111,
            'name' => 'worker2',
            'email' =>'worker2@gmail.com',
            'password' => bcrypt('12341234'),
            'address' => 'damas','bio'=>'none','city_id'=>1,'place_id'=>1,
            'phone' =>'09111111',
            'contact' => 'hello',
            'photo'=> 'uploads/users_photo/test',
            'photo_profile'=> 'uploads/users_photo/test',
            'permission_id'=>3,
        ]]);
        //
    }
}
