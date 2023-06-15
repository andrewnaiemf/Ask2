<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicScheduleDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinic_schedule_doctors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_schedule_id');
            $table->string('doctor_name')->nullable();
            $table->string('doctor_cost')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->foreign('clinic_schedule_id')->references('id')->on('clinic_schedule');
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
        Schema::dropIfExists('clinic_schedule_doctors');
    }
}
