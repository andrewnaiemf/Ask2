<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddonsTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addons_translation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('addon_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->timestamps();
            $table->foreign('addon_id')->references('id')->on('addons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addons_translation');
    }
}
