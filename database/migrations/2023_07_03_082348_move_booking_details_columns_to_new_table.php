<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveBookingDetailsColumnsToNewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn(['year', 'month', 'day', 'time']);
            });

            Schema::create('booking_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('booking_id');
                $table->string('year');
                $table->string('month');
                $table->string('day');
                $table->string('time');
                $table->softDeletes();
                $table->timestamps();
                $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');

            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            Schema::table('booking_details', function (Blueprint $table) {
                $table->dropForeign(['booking_id']);
            });
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('year');
                $table->string('month');
                $table->string('day');
                $table->string('time');
            });
        });
    }
}
