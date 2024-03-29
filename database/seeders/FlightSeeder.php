<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\Airline;
use App\Models\City;
use App\Models\Flight;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        $airlines = Airline::query()
            ->with('planes');

        foreach ($airlines->cursor() as $airline) {
            $departure  = $faker->dateTimeBetween('now', '+2 days');
            $arrival    = $faker->dateTimeBetween($departure, '+2 days');
            foreach ($airline->planes as $plane) {
                Flight::query()->create([
                    "flight_number" => $faker->unique()->numberBetween(100, 999),
                    'airline_id' => $airline->id,
                    'plane_id' => $plane->id,
                    "origin_id" => City::query()->inRandomOrder()->first()->id,
                    "destination_id" => City::query()->inRandomOrder()->first()->id,
                    "departure" => $departure,
                    "arrival" => $arrival,
                    "seats" => $plane->capacity,
                    "remain_seats" => rand(1, $plane->capacity),
                    "price" => rand(100, 1000),
                    'status' => $faker->boolean(),
                    'gate_number' => $faker->randomDigit(),
                    'crew_id' => 1
                ]);
            }
        }
    }
}
