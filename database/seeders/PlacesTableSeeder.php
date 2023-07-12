<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Place;
use App\Models\City;

class PlacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       /* DB::table('places')->insert([
            ['place' => 'user'],
            ['place' => 'admin'],
        ]);*/
        $cityDamascusId = $this->getCityIdByName('Damascus');
        $cityAleppoId = $this->getCityIdByName('Aleppo');
        $cityLatakiaId = $this->getCityIdByName('Latakia');
        $cityHamaId = $this->getCityIdByName('Hama');

        $places = [
            ['city_id' => $cityDamascusId, 'place' => 'حي المزة'],
            ['city_id' => $cityDamascusId, 'place' => 'حي المهاجرين'],
            ['city_id' => $cityDamascusId, 'place' => 'حي القابون'],
            ['city_id' => $cityDamascusId, 'place' => 'حي جرمانا'],


            ['city_id' => $cityAleppoId, 'place' => ' حي الجامعة' ],
            ['city_id' => $cityAleppoId, 'place' => 'حي الأزرق'],
            ['city_id' => $cityAleppoId, 'place' => 'حي الشعار'],
            ['city_id' => $cityAleppoId, 'place' => 'حي الأنصاري'],


            ['city_id' => $cityLatakiaId, 'place' => 'حي الشرفية'],
            ['city_id' => $cityLatakiaId, 'place' => 'حي المطار'],
            ['city_id' => $cityLatakiaId, 'place' => 'حي الأسد'],
            ['city_id' => $cityLatakiaId, 'place' => 'حي المزارع'],

            ['city_id' => $cityHamaId, 'place' => 'حي المحطة'],
            ['city_id' => $cityHamaId, 'place' => 'حي الحديقة'],
            ['city_id' => $cityHamaId, 'place' => 'حي الشهباء'],




        ];
        Place::insert($places);
    }

    private function getCityIdByName($name)
    {
        return City::where('city', $name)->value('id');
    }
}
