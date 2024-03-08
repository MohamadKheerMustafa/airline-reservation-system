<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('flight_id');
            $table->date('reservation_date');
            $table->string('seat_preference')->nullable()->comment("Passenger's preference for seat (e.g., Window, Aisle).");
            $table->text('special_requests')->nullable()->comment("Any special requests made by the passenger.");
            $table->text('addtionalNotes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('flight_id')->references('id')->on('flights');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
