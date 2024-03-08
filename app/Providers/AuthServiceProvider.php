<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\City;
use App\Models\Plane;
use App\Policies\AirlinePolicy;
use App\Policies\AirportPolicy;
use App\Policies\CityPolicy;
use App\Policies\PlanePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Airline::class => AirlinePolicy::class,
        Airport::class => AirportPolicy::class,
        City::class => CityPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

    }
}
