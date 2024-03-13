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
        Schema::create('session_data', function (Blueprint $table) {
            $table->id();
            $table->integer('raceId')->nullable();
            $table->text('meetingName');
            $table->text('meetingLocation');
            $table->text('meetingCountry');
            $table->integer('key');
            $table->text('type');
            $table->text('name');
            $table->timestamp('startDate')->nullable();
            $table->timestamp('endDate')->nullable();
            $table->json('driversData');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_data');
    }
};
