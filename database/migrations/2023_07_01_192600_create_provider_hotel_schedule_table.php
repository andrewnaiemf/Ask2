<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderHotelScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->time('arrival_start_time')->nullable();
            $table->time('arrival_end_time')->nullable();
            $table->time('departure_start_time')->nullable();
            $table->time('departure_end_time')->nullable();
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_schedules');
    }
}
