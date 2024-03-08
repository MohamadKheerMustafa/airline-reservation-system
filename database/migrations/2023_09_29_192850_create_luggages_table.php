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
        Schema::create('luggages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->unsignedBigInteger('passenger_id');
            $table->integer('standard_quantity')->default(0);
            $table->integer('additional_quantity')->default(0);
            $table->decimal('additional_price', 10, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('reservation_id')->references('id')->on('reservations');
            $table->foreign('passenger_id')->references('id')->on('passengers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('luggages');
    }
};
