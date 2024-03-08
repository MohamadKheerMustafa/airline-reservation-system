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
        Schema::table('luggages', function (Blueprint $table) {
            $table->dropForeign(['reservation_id']);
            $table->dropForeign(['passenger_id']);
            $table->dropColumn(['reservation_id', 'passenger_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('luggages', function (Blueprint $table) {
            //
        });
    }
};
