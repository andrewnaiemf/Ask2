<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_booking_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->year('year');
            $table->string('arrival_month');
            $table->string('arrival_day');
            $table->time('arrival_time');
            $table->string('departure_month');
            $table->string('departure_day');
            $table->time('departure_time');
            $table->decimal('total_cost', 8, 2);
            $table->integer('adults');
            $table->integer('kids');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_booking_details', function (Blueprint $table) {
            Schema::dropIfExists('hotel_booking_details');
        });
    }
}
