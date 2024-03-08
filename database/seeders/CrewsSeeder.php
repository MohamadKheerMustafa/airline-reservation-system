<?php

namespace Database\Seeders;

use App\Models\Airline;
use App\Models\Crew;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CrewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $airline = Airline::all();
        foreach ($airline as $crewAirline) {
            Crew::create([
                'airline_id' => $crewAirline->id,
                'name' => $crewAirline->code . '-Crew'
            ]);
        }
    }
}
