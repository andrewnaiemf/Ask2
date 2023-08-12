<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsFromHotelSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_schedules', function (Blueprint $table) {
            $table->dropColumn('arrival_end_time');
            $table->dropColumn('departure_start_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_schedules', function (Blueprint $table) {
            $table->time('arrival_end_time')->nullable();
            $table->time('departure_start_time')->nullable();
        });
    }
}
