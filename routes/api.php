<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\admin\{
    AirlineController,
    AirportController,
    CityController,
    CountryController,
    CrewController,
    CustomerController,
    FlightController,
    luggagesController,
    PassengerController,
    PaymentController,
    planeController,
    ProfileController,
    ReservationController,
    SeatController,
    TicketController,
};
use App\Http\Controllers\HomeController;
use App\Models\CrewMember;
use App\Models\Reservation;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::post('/signup', [AuthController::class, 'sign_up']);

// Route::get('te', [planeController::class, 'te']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::apiResource('Countries', CountryController::class);
    Route::apiResource('Airline', AirlineController::class);
    Route::apiResource('Airport', AirportController::class);
    Route::apiResource('City', CityController::class);
    Route::apiResource('Plane', planeController::class);
    Route::apiResource('Flight', FlightController::class);
    Route::apiResource('Crews', CrewController::class);
    Route::post('CrewsMember', [CrewController::class, 'storeCrewsMember']);
    Route::apiResource('Passengers', PassengerController::class);
    Route::apiResource('Reservation', ReservationController::class);
    Route::apiResource('Seats', SeatController::class);
    Route::apiResource('Payment', PaymentController::class);
    Route::apiResource('luggages', luggagesController::class);

    Route::prefix('v1/Planes')->group(function () {
        Route::post('getPlanesByAirline', [FlightController::class, 'getPlanesByAirline']);
    });

    Route::prefix('v1/Tickets')->group(function () {
        Route::get('Tickets', [TicketController::class, 'index']);
        Route::get('seatsFlight/{reservation_id}', [TicketController::class, 'seatsFlight']);
        Route::post('book', [TicketController::class, 'book']);
        Route::delete('cancel/{id}', [TicketController::class, 'cancel']);
        Route::put('changeStatus/{id}', [TicketController::class, 'changeStatus']);
    });

    Route::prefix('v1/TicketsAdmin')->group(function () {
        Route::get('userTickets', [TicketController::class, 'userTickets']);
        Route::get('TicketsPending', [TicketController::class, 'TicketsPending']);
        Route::get('TicketsCancelled', [TicketController::class, 'TicketsCancelled']);
        Route::get('TicketsBooked', [TicketController::class, 'TicketsBooked']);
    });

    Route::prefix('v1/UserRoutes')->group(function () {
        Route::get('allFlights', [TicketController::class, 'allFlights']);
        Route::get('showFlights/{user_id}', [TicketController::class, 'showFlights']);
        Route::get('showFlightsMe/{user_id}', [TicketController::class, 'showFlightsMe']);
        Route::get('getCountries', [CountryController::class, 'getCountries']);
        Route::get('getAirline', [AirlineController::class, 'getAirline']);
        Route::get('getPlane', [AirlineController::class, 'getPlane']);
        Route::get('getAirport', [AirlineController::class, 'getAirport']);
        Route::get('getreservationForUser', [ReservationController::class, 'getreservationForUser']);
        Route::get('getCrewsByAirline/{airline_id}', [CrewController::class, 'getCrewsByAirline']);
    });

    Route::prefix('v1/search')->group(function () {
        Route::get('searchAirline/{query}', [AirlineController::class, 'searchAirline']);
        Route::get('searchAirport/{query}', [AirportController::class, 'searchAirport']);
        Route::get('searchCity/{query}', [CityController::class, 'searchCity']);
        Route::get('searchCountry/{query}', [CountryController::class, 'searchCountry']);
        Route::get('searchCrew/{query}', [CrewController::class, 'searchCrew']);
        Route::get('searchUser/{query}', [CustomerController::class, 'searchUser']);
        Route::get('searchFlight/{query}', [FlightController::class, 'searchFlight']);
        Route::get('searchPassengers/{query}', [PassengerController::class, 'searchPassengers']);
        Route::get('searchPayment/{query}', [PaymentController::class, 'searchPayment']);
        Route::get('searchPlane/{query}', [planeController::class, 'searchPlane']);
        Route::get('searchReservation/{query}', [ReservationController::class, 'searchReservation']);
        Route::get('searchSeat/{query}', [SeatController::class, 'searchSeat']);
        Route::get('searchTicket/{query}', [TicketController::class, 'searchTicket']);
    });

    Route::prefix('v1/Customers')->group(function () {
        Route::get('allCustomer', [CustomerController::class, 'index']);
        Route::get('showaSpecificCustomer/{id}', [CustomerController::class, 'show']);
        Route::delete('deleteCustomer/{id}', [CustomerController::class, 'destroy']);
        Route::get('admins', [CustomerController::class, 'admins']);
        Route::post('addEmployee', [AuthController::class, 'addEmployee']);
    });

    Route::prefix('v1/Profile')->group(function () {
        Route::post('UpdateProfile', [ProfileController::class, 'updateProfile']);
        Route::post('UpdatePassword', [ProfileController::class, 'updatePassword']);
    });

    Route::prefix('v1/Payment')->group(function () {
        Route::post('PayTickets', [PaymentController::class, 'PayTicket']);
        Route::get('paymentInfo/{ticket_id}', [PaymentController::class, 'paymentInfo']);
        Route::get('userPayment', [PaymentController::class, 'userPayment']);
    });

    Route::get('dashboard', [HomeController::class, 'statistics']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
