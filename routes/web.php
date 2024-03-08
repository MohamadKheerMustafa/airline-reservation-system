<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $citiesByCountry = [
        'Iraq' => ['Erbil', 'Baghdad', 'Basra', 'Najaf', 'Sulaimany', 'Nasiriyah'],
        'Jordan' => ['Amman'],
        'Turkey' => ['Antalya', 'Istanbul', 'Ankara', 'Antalya', 'Adana', 'Diyarbakir', 'Gaziantep', 'Trabzon', 'Mugla'],
        'Greece' => ['Athens'],
        'Bahrain' => ['Bahrain'],
        'Lebanon' => ['Beirut'],
        'Syria' => ['Damascus', 'Aleppo'],
        'United Arab Emirates' => ['Dubai', 'Abu', 'sharjah'],
        'Germany' => ['Dusseldorf', 'Frankfurt', 'Munich', 'Berlin', 'Stuttgart', 'Nuremberg', 'Cologne', 'Hanover'],
        'Sweden' => ['Gothenburg', 'Malmo', 'Stockholm'],
        'United Kingdom' => ['London', 'Manchester', 'Birmingham'],
        'Norway' => ['Oslo'],
        'Iran' => ['Tehran', 'Orumiyeh', 'Urmia'],
        'Austria' => ['Vienna'],
        'Netherlands' => ['Eindhoven', 'Amsterdam', 'Rotterdam'],
        'Denmark' => ['Copenhagen'],
        'Saudi Arabia' => ['Jeddah', 'Madina'],
        'Cyprus' => ['Larnaca'],
        'Egypt' => ['Cairo'],
        'Qatar' => ['Doha'],
        'Georgia' => ['Tbilisi', 'Batumi'],
        'Armenia' => ['Yerevan'],
        'Azerbaijan' => ['Baku'],
        'Belarus' => ['Minsk'],
        'Ukraine' => ['Kiev', 'Odessa'],
        'Romania' => ['Bucharest'],
        'Czech Republic' => ['Prague'],
    ];
    $cityToCountry = [];
    foreach ($citiesByCountry as $country => $cities) {
        foreach ($cities as $city) {
            $cityToCountry[$city] = $country;
        }
    }

    $countries = DB::table('countries')->pluck('id', 'name');
    
    $cities = DB::table('cities')->get();
    foreach ($cities as $city) {
        $countryName = $cityToCountry[$city->name] ?? null;
        $countryId = $countries[$countryName] ?? null;
        if ($countryId) {
            DB::table('cities')
                ->where('id', $city->id)
                ->update(['country_id' => $countryId]);
        }
    }
});
