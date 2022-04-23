<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCattleLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cattle_locations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cattle_id');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();

            $table->boolean('green_zone')->nullable();
            $table->boolean('purple_zone')->nullable();
            $table->boolean('orange_zone')->nullable();
            $table->boolean('red_zone')->nullable();
            $table->boolean('grazing')->nullable();
            $table->boolean('standing')->nullable();
            $table->boolean('sitting')->nullable();

            $table->date('location_date')->nullable();

            $table->timestamps();
            $table->integer('added_by')->nullable();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cattle_locations');
    }
}
