<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelServicesTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_services_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_service_id');
            $table->string('locale')->index();

            $table->string('name');
            $table->unique(['hotel_service_id', 'locale']);
            $table->foreign('hotel_service_id')->references('id')->on('hotel_services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_services_translations');
    }
}
