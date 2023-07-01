<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->unsignedBigInteger('room_type_id');
            $table->integer('numbers')->default(1);
            $table->integer('adults')->nullable();
            $table->integer('kids')->nullable();
            $table->enum('outdoor', ['Balcony', 'Head', 'View'])->nullable();
            $table->json('images')->nullable();
            $table->string('cost')->nullable();
            $table->timestamps();
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->foreign('room_type_id')->references('id')->on('room_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
