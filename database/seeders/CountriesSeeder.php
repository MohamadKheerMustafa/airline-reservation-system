<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Iraq'],
            ['name' => 'Jordan'],
            ['name' => 'Turkey'],
            ['name' => 'Greece'],
            ['name' => 'Bahrain'],
            ['name' => 'Lebanon'],
            ['name' => 'Syria'],
            ['name' => 'United Arab Emirates'],
            ['name' => 'Germany'],
            ['name' => 'Sweden'],
            ['name' => 'United Kingdom'],
            ['name' => 'Norway'],
            ['name' => 'Iran'],
            ['name' => 'Austria'],
            ['name' => 'Netherlands'],
            ['name' => 'Denmark'],
            ['name' => 'Saudi Arabia'],
            ['name' => 'Cyprus'],
            ['name' => 'Egypt'],
            ['name' => 'Qatar'],
            ['name' => 'Georgia'],
            ['name' => 'Armenia'],
            ['name' => 'Azerbaijan'],
            ['name' => 'Belarus'],
            ['name' => 'Ukraine'],
            ['name' => 'Spain'],
            ['name' => 'Romania'],
            ['name' => 'Czech Republic'],
        ];

        DB::table('countries')->insert($countries);
    }
}
