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
        Schema::create('crewsMembers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crews_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('position');
            $table->date('date_of_birth');
            $table->string('nationality');
            $table->foreign('crews_id')->references('id')->on('crews')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crewsMembers');
    }
};
