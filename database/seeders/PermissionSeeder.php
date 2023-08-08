<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
         ['permission' => 'user'],
         ['permission' => 'admin'],
         ['permission' => 'work'],
         ['permission' => 'banned_user'],
        ]);

    }
}
