<?php

namespace Database\Seeders;

use App\Models\Airport;
use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = City::pluck("name", "id")->toArray();

        foreach ($cities as $city_id => $city_name) {
            Airport::create([
                "city_id" => $city_id,
                "name" => $city_name . " Airport",
            ]);
        }
    }
}
